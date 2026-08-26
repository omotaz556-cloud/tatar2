<?php
/**
 * Build and broadcast the end-of-era WW victory report to every player inbox.
 */

if (!function_exists('tz_winner_report_gather')) {

    /**
     * Collect winner / top stats used by the victory report and inbox message.
     *
     * @return array<string,mixed>
     */
    function tz_winner_report_gather($database)
    {
        $accessLimit = (defined('INCLUDE_ADMIN') && INCLUDE_ADMIN) ? 10 : 8;
        $tribeFilter = 'u.tribe IN (1,2,3,6,7,8,9)';
        $out = [
            'vref' => 0,
            'village' => '',
            'winner_uid' => 0,
            'winner_name' => '',
            'alliance_id' => 0,
            'alliance_tag' => '',
            'finished_at' => 0,
            'top_pop' => null,
            'top_att' => null,
            'top_def' => null,
            'top_hero' => null,
            'gold_prize' => defined('WW_WINNER_GOLD_PRIZE') ? (int) WW_WINNER_GOLD_PRIZE : 50000,
            'server_name' => defined('SERVER_NAME') ? SERVER_NAME : '',
            'world_label' => defined('SERVER_WORLD_NUMBER') ? SERVER_WORLD_NUMBER : '',
        ];

        $q = "SELECT f.vref, f.ww_lastupdate, v.name AS village_name, v.owner AS owner_id,
                     u.username, a.id AS alliance_id, a.tag AS alliance_tag
              FROM " . TB_PREFIX . "fdata f
              LEFT JOIN " . TB_PREFIX . "vdata v ON v.wref = f.vref
              LEFT JOIN " . TB_PREFIX . "users u ON u.id = v.owner
              LEFT JOIN " . TB_PREFIX . "alidata a ON a.id = u.alliance
              WHERE f.f99 = '100' AND f.f99t = '40'
              LIMIT 1";
        $row = $database->query_return($q);
        if (!empty($row[0])) {
            $r = $row[0];
            $out['vref'] = (int) $r['vref'];
            $out['village'] = (string) $r['village_name'];
            $out['winner_uid'] = (int) $r['owner_id'];
            $out['winner_name'] = (string) $r['username'];
            $out['alliance_id'] = (int) $r['alliance_id'];
            $out['alliance_tag'] = (string) $r['alliance_tag'];
            $out['finished_at'] = (int) $r['ww_lastupdate'];
        }

        $q = "SELECT u.id AS userid, u.username,
                     (SELECT SUM(v.pop) FROM " . TB_PREFIX . "vdata v WHERE v.owner = u.id) AS totalpop
              FROM " . TB_PREFIX . "users u
              WHERE u.access < " . (int) $accessLimit . " AND " . $tribeFilter . "
              ORDER BY totalpop DESC, u.username ASC LIMIT 1";
        $r = $database->query_return($q);
        $out['top_pop'] = !empty($r[0]) ? $r[0] : null;

        $q = "SELECT u.id AS userid, u.username, u.apall
              FROM " . TB_PREFIX . "users u
              WHERE u.access < " . (int) $accessLimit . " AND " . $tribeFilter . "
              ORDER BY u.apall DESC, u.username ASC LIMIT 1";
        $r = $database->query_return($q);
        $out['top_att'] = !empty($r[0]) ? $r[0] : null;

        $q = "SELECT u.id AS userid, u.username, u.dpall
              FROM " . TB_PREFIX . "users u
              WHERE u.access < " . (int) $accessLimit . " AND " . $tribeFilter . "
              ORDER BY u.dpall DESC, u.username ASC LIMIT 1";
        $r = $database->query_return($q);
        $out['top_def'] = !empty($r[0]) ? $r[0] : null;

        $q = "SELECT h.uid AS userid, u.username
              FROM " . TB_PREFIX . "hero h
              INNER JOIN " . TB_PREFIX . "users u ON u.id = h.uid
              WHERE h.dead = 0 AND u.access < " . (int) $accessLimit . " AND " . $tribeFilter . "
              ORDER BY h.experience DESC, u.username ASC LIMIT 1";
        $r = $database->query_return($q);
        $out['top_hero'] = !empty($r[0]) ? $r[0] : null;

        return $out;
    }

    /**
     * HTML body for the inbox victory report (Arabic when lang strings exist).
     */
    function tz_winner_report_message_html(array $data)
    {
        $hl = static function ($text) {
            return '<span style="color:#71D000;font-weight:bold">'
                . htmlspecialchars((string) $text, ENT_QUOTES, 'UTF-8') . '</span>';
        };
        $name = static function ($row) use ($hl) {
            if (empty($row) || empty($row['username'])) {
                return $hl('—');
            }
            return $hl($row['username']);
        };

        $server = htmlspecialchars((string) ($data['server_name'] ?? ''), ENT_QUOTES, 'UTF-8');
        $ally = $hl($data['alliance_tag'] !== '' ? $data['alliance_tag'] : '—');
        $winner = $hl($data['winner_name'] !== '' ? $data['winner_name'] : '—');
        $gold = $hl(number_format((int) ($data['gold_prize'] ?? 0)));
        $world = !empty($data['world_label'])
            ? ' ' . sprintf(
                defined('WINNER_RPT_WORLD_SUFFIX') ? WINNER_RPT_WORLD_SUFFIX : 'للعالم %s',
                htmlspecialchars((string) $data['world_label'], ENT_QUOTES, 'UTF-8')
            )
            : '';

        $title = sprintf(
            defined('WINNER_RPT_DEAR') ? WINNER_RPT_DEAR : 'اعزائنا لاعبي %s',
            $server
        );

        $parts = [];
        $parts[] = '<b>' . $title . '</b>';
        $parts[] = sprintf(
            defined('WINNER_RPT_INTRO') ? WINNER_RPT_INTRO
                : 'وبعد أيام طويلة من الجهد والتعب والعمل بشغف استطاع تحالف %1$s أن يبنوا معجزتهم التي نافسهم عليها بقية اللاعبين، حيث رصدوا لها الملايين من الموارد.',
            $ally
        );
        $parts[] = sprintf(
            defined('WINNER_RPT_WINNER') ? WINNER_RPT_WINNER
                : 'نتيجة التنسيق الجيد والعمل الجماعي تمكن أخيراً العمال في %1$s من تشييد أروع مبنى في اللعبة وبهذا يستلم %2$s لقب الفائز في هذه العالم وجائزة %3$s ذهبية وسيسجل اسمه وتحالفه في لائحة ابطال %4$s%5$s.',
            $ally,
            $winner,
            $gold,
            $server,
            $world
        );
        $parts[] = sprintf(
            defined('WINNER_RPT_EMPIRE') ? WINNER_RPT_EMPIRE
                : 'نستطيع الآن أن نقول بأن %s هو الحاكم المطلق لأكبر امبراطورية في اللعبة.',
            $name($data['top_pop'] ?? null)
        );
        $parts[] = sprintf(
            defined('WINNER_RPT_ATTACKER') ? WINNER_RPT_ATTACKER
                : 'وقام %s بمهاجمة الأعداء وذبحهم أكثر من أي شخص أخر، لذلك يعتبر القائد الأقوى.',
            $name($data['top_att'] ?? null)
        );
        $parts[] = sprintf(
            defined('WINNER_RPT_DEFENDER') ? WINNER_RPT_DEFENDER
                : 'أما %s فقد كان المدافع الأعظم فقد قام بذبح أعدائه المهاجمين وملأ الأرض حول قراه بدمهم.',
            $name($data['top_def'] ?? null)
        );
        $parts[] = sprintf(
            defined('WINNER_RPT_HERO') ? WINNER_RPT_HERO
                : 'وكان %s قد درب بطله وهاجم وقضى على الأعداء ويستحق أن نقول أن لديه بطل الأبطال.',
            $name($data['top_hero'] ?? null)
        );
        $parts[] = sprintf(
            defined('WINNER_RPT_THANKS') ? WINNER_RPT_THANKS
                : 'نحن فريق %s يجب ان نشكر كل من واصل اللعب حتى النهاية باخلاص.',
            $server
        );
        $parts[] = '<br /><a href="winner.php">&raquo; '
            . (defined('WINNER_RPT_FORWARD') ? WINNER_RPT_FORWARD : 'إلى الأمام')
            . '</a>';

        return implode('<br /><br />', $parts);
    }

    /**
     * Send the victory report once to every playable account inbox.
     *
     * @return bool true when a broadcast was performed
     */
    function tz_winner_report_broadcast_all($database, $wid = 0, $ownerId = 0)
    {
        $wid = (int) $wid;
        $ownerId = (int) $ownerId;

        if (method_exists($database, 'recordMilestoneIfFirst')) {
            $ok = $database->recordMilestoneIfFirst(
                'world_wonder_victory_message',
                $ownerId > 0 ? $ownerId : 1,
                $wid > 0 ? $wid : 1,
                'inbox'
            );
            if (!$ok) {
                return false;
            }
        }

        $data = tz_winner_report_gather($database);
        if ($data['winner_uid'] <= 0) {
            return false;
        }

        $subject = defined('WINNER_RPT_MSG_SUBJECT')
            ? WINNER_RPT_MSG_SUBJECT
            : (defined('WINNER_RPT_PAGE_TITLE') ? WINNER_RPT_PAGE_TITLE : 'نهاية العالم');
        $subjectHtml = '<span style="color:green;">'
            . htmlspecialchars($subject, ENT_QUOTES, 'UTF-8') . '</span>';

        $body = '[message]' . tz_winner_report_message_html($data) . '[/message]';
        $subjectEsc = $database->escape($subjectHtml);
        $bodyEsc = $database->escape($body);

        $result = mysqli_query(
            $database->dblink,
            'SELECT id FROM ' . TB_PREFIX . 'users WHERE id > 5 ORDER BY id ASC'
        );
        if (!$result) {
            return false;
        }

        $rows = [];
        $time = time();
        while ($user = mysqli_fetch_assoc($result)) {
            $uid = (int) $user['id'];
            $rows[] = '(' . $uid . ',1,\'' . $subjectEsc . '\',\'' . $bodyEsc
                . '\',0,0,0,' . $time . ',0,0,0,0,0,0)';
        }

        if (!$rows) {
            return false;
        }

        // Chunk inserts to avoid oversized packets on large worlds.
        foreach (array_chunk($rows, 200) as $chunk) {
            $sql = 'INSERT INTO ' . TB_PREFIX . 'mdata
                (target, owner, topic, message, viewed, archived, send, time,
                 deltarget, delowner, alliance, player, coor, report)
                VALUES ' . implode(',', $chunk);
            mysqli_query($database->dblink, $sql);
        }

        return true;
    }
}
