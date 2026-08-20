<?php
#################################################################################
##                                                                             ##
##  Project:       Novaterra                                                   ##
##  Filename:      GameEngine/Admin/function.php                               ##
##  Purpose:       Admin Control Panel — session/auth gate and action router   ##
##  License:       Proprietary — original work, part of Novaterra project      ##
##                 (rewritten from scratch, no derivation from prior authors)  ##
##                                                                             ##
#################################################################################

include_once(__DIR__ . "/../Artifacts.php");
include_once(__DIR__ . "/../Units.php");
include_once(__DIR__ . "/../Generator.php");

/**
 * AdminGateway
 *
 * Central helper for the Admin Control Panel: verifies the current
 * session is an authenticated admin, dispatches GET/POST "action"
 * requests coming from the ACP templates, and exposes a couple of
 * small display helpers used by those templates.
 *
 * Kept as class `funct` (and the same public method names) because
 * Admin/admin.php and several .tpl templates already call it that
 * way — renaming the public surface would require touching every
 * caller across the panel for no functional benefit.
 */
class funct
{
    /** Minimum access level considered "logged in" for the ACP. */
    private const MIN_ACCESS_LEVEL = MULTIHUNTER;

    /**
     * Human-readable names for the building reference IDs (1-50),
     * used by the village/edit-village admin templates.
     */
    private const BUILDING_NAMES = [
        1  => "Woodcutter",
        2  => "Clay Pit",
        3  => "Iron Mine",
        4  => "Cropland",
        5  => "Sawmill",
        6  => "Brickyard",
        7  => "Iron Foundry",
        8  => "Grain Mill",
        9  => "Bakery",
        10 => "Warehouse",
        11 => "Granary",
        12 => "Blacksmith",
        13 => "Armoury",
        14 => "Tournament Square",
        15 => "Main Building",
        16 => "Rally Point",
        17 => "Marketplace",
        18 => "Embassy",
        19 => "Barracks",
        20 => "Stable",
        21 => "Workshop",
        22 => "Academy",
        23 => "Cranny",
        24 => "Town Hall",
        25 => "Residence",
        26 => "Palace",
        27 => "Treasury",
        28 => "Trade Office",
        29 => "Great Barracks",
        30 => "Great Stable",
        31 => "City Wall",
        32 => "Earth Wall",
        33 => "Palisade",
        34 => "Stonemason's Lodge",
        35 => "Brewery",
        36 => "Trapper",
        37 => "Hero's Mansion",
        38 => "Great Warehouse",
        39 => "Great Granary",
        40 => "Wonder of the World",
        41 => "Horse Drinking Trough",
        42 => "Stone Wall",
        43 => "Makeshift Wall",
        44 => "Command Center",
        45 => "Waterworks",
        46 => "Hospital",
        47 => "Defensive Wall",
        48 => "Big Hospital",
        49 => "Great Workshop",
        50 => "Barricade",
    ];

    /**
     * True when the current session belongs to a logged-in admin
     * with at least MULTIHUNTER access.
     */
    public function CheckLogin(): bool
    {
        return !empty($_SESSION['id'])
            && isset($_SESSION['access'])
            && $_SESSION['access'] >= self::MIN_ACCESS_LEVEL;
    }

    /**
     * Send the browser back to wherever it came from, but only if
     * that location is on this same host — otherwise fall back to
     * the ACP home. Prevents this being used as an open redirect.
     */
    private function returnToCaller(): void
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? '';

        if ($referer !== '') {
            $parsed = parse_url($referer);
            $sameHost = empty($parsed['host']) || $parsed['host'] === $_SERVER['HTTP_HOST'];

            if ($sameHost) {
                header('Location: ' . $referer);
                exit;
            }
        }

        header('Location: admin.php');
        exit;
    }

    /**
     * Dispatch a GET-triggered admin action (village/user/ban/hero
     * management, artifact administration, logout). Mirrors the
     * action names already emitted by the ACP templates.
     */
    public function Act(array $get): void
    {
        global $admin, $database, $units, $generator;

        $artifacts = new Artifacts();
        $action = $get['action'] ?? '';

        switch ($action) {

            case "recountPop":
                $admin->recountPop($get['did']);
                $admin->recountCP($get['did']);
                break;

            case "recountPopUsr":
                $admin->recountPopUser($get['uid']);
                break;

            case "StopDel":
                // No-op: cancels an in-progress account deletion countdown
                // from the template's point of view; nothing to persist here.
                break;

            case "delVil":
                $keepOwner = isset($get['mode']) && $get['mode'] == 1;
                if ($keepOwner) {
                    $admin->DelVillage($get['did'], 1);
                } else {
                    $admin->DelVillage($get['did']);
                }
                break;

            case "delBan":
                $admin->DelBan($get['uid'], $get['id']);
                break;

            case "delIpBan":
                if (isset($get['id'])) {
                    $admin->DelIpBan($get['id']);
                }
                break;

            case "addBan":
                $expiresAt = !empty($get['time']) ? time() + $get['time'] : '';
                $targetUid = is_numeric($get['uid'])
                    ? $get['uid']
                    : $database->getUserField(addslashes($get['uid']), 'id', 1);

                $admin->AddBan($targetUid, $expiresAt, $get['reason']);
                break;

            case "delOas":
                $units->returnTroops($get['did'], 1);
                $database->removeOases($get['oid']);
                break;

            case "logout":
                $this->LogOut();
                break;

            case "delArtifact":
                if (isset($_GET['artid']) && is_numeric($_GET['artid'])) {
                    $database->updateArtifactDetails($_GET['artid'], ['del' => 1]);
                }
                break;

            case "returnArtifact":
                $this->handleReturnArtifact($_GET, $artifacts, $database);
                break;

            case "addArtifacts":
                $this->handleAddArtifacts($_POST, $artifacts, $database);
                break;

            case "addWWVillages":
                $this->handleAddWWVillages($_POST, $artifacts, $database);
                break;

            case "killHero":
                $this->handleKillHero($get, $database);
                return; // handler always redirects+exits itself

            case "reviveHero":
                $this->handleReviveHero($get, $database);
                return; // handler always redirects+exits itself

            case "addHero":
                $this->handleAddHero($get, $database);
                return; // handler always redirects+exits itself
        }

        $this->returnToCaller();
    }

    /** Return a Natars-owned artifact back to the Natars account. */
    private function handleReturnArtifact(array $get, Artifacts $artifacts, $database): void
    {
        if (!isset($get['artid']) || !is_numeric($get['artid'])) {
            header("Location: admin.php");
            exit;
        }

        $info = $database->getArtefactDetails($get['artid'], $get['del'] ?? null);

        if (empty($info)) {
            header("Location: admin.php");
            exit;
        }

        if ($info['owner'] == Artifacts::NATARS_UID) {
            $database->updateArtifactDetails($get['artid'], ['del' => 0]);
            header("Location: admin.php");
            exit;
        }

        $artifacts->returnArtifactToNatars($info);
    }

    /** Grant one or more artifacts of a chosen type to a player (or the Natars). */
    private function handleAddArtifacts(array $post, Artifacts $artifacts, $database): void
    {
        $selected = $post['selectedArtifact'] ?? null;
        $quantity = (int)($post['artifactQuantity'] ?? 0);
        $playerId = (int)($post['playerId'] ?? 0);

        $inputsValid = !empty($selected)
            && is_numeric($quantity) && $quantity > 0 && $quantity <= 999
            && is_numeric($playerId)
            && strpos($selected, ':') !== false
            && $database->getUserField($playerId, "username", 0) !== "[?]";

        if (!$inputsValid) {
            header("Location: admin.php?p=natars&error=0");
            exit;
        }

        // Format is "size:index:type" — split out which catalog and slot to use.
        [, $slotIndex, $catalogType] = explode(":", $selected);

        $catalog = array_merge(Artifacts::NATARS_ARTIFACTS, Artifacts::NATARS_WW_BUILDING_PLANS);
        $chosen = $catalog[$catalogType][$slotIndex - 1] ?? null;

        if (empty($chosen)) {
            header("Location: admin.php?p=natars&error=1");
            exit;
        }

        $chosen['quantity'] = $quantity;
        $grantBatch = [$catalogType => [$chosen]];

        $artifacts->addArtifactVillages($grantBatch, $playerId, $playerId == Artifacts::NATARS_UID);
    }

    /** Create one or more Wonder-of-the-World village slots for a player. */
    private function handleAddWWVillages(array $post, Artifacts $artifacts, $database): void
    {
        $count = (int)($post['numberOfVillages'] ?? 0);
        $playerId = (int)($post['playerId'] ?? 0);

        $inputsValid = is_numeric($count) && $count > 0 && $count <= 999
            && is_numeric($playerId)
            && $database->getUserField($playerId, "username", 0) !== "[?]";

        if (!$inputsValid) {
            header("Location: admin.php?p=natars&error=2");
            return;
        }

        $artifacts->createWWVillages($count, $playerId, $playerId == Artifacts::NATARS_UID);
    }

    /** Kill a player's hero, searching every village/defense/movement/oasis they might be in. */
    private function handleKillHero(array $get, $database): void
    {
        $villages = $database->getProfileVillages($get['uid']);

        foreach ($villages as $village) {
            if ($database->FindHeroInVil($village['wref'])
                || $database->FindHeroInDef($village['wref'])
                || $database->FindHeroInMovement($village['wref'])
            ) {
                break;
            }
        }

        // Oases are checked separately regardless of the loop outcome above,
        // matching the panel's existing "search everywhere" behaviour.
        $database->FindHeroInOasis($get['uid']);
        $database->KillMyHero($get['uid']);

        header("Location: admin.php?p=player&uid=" . $get['uid'] . "&kc=1");
        exit;
    }

    /** Bring a dead hero back to life, unless a living one already exists for that player. */
    private function handleReviveHero(array $get, $database): void
    {
        $uid = (int)$get['uid'];

        $alive = mysqli_fetch_array(
            $database->query(
                "SELECT COUNT(*) as Total FROM " . TB_PREFIX . "hero " .
                "WHERE uid=" . $uid . " AND (dead = 0 OR inrevive = 1 OR intraining = 1)"
            ),
            MYSQLI_ASSOC
        );

        if ($alive['Total'] > 0) {
            header("Location: admin.php?p=player&uid=" . $uid . "&re=1");
            exit;
        }

        $heroId = (int)$get['hid'];
        $hero = mysqli_fetch_array($database->query(
            "SELECT * FROM " . TB_PREFIX . "hero WHERE heroid = " . $heroId . " AND uid=" . $uid
        ));

        $database->query("UPDATE " . TB_PREFIX . "units SET hero = 1 WHERE vref = " . (int)$hero['wref']);
        $database->query(
            "UPDATE " . TB_PREFIX . "hero SET `dead` = '0', `inrevive` = '0', `health` = '100', " .
            "`lastupdate` = " . time() . " WHERE `heroid` = " . $heroId . " AND `uid` = " . $uid
        );

        header("Location: admin.php?p=player&uid=" . $uid . "&rc=1");
        exit;
    }

    /** Spawn a fresh hero of the requested unit type in a player's capital. */
    private function handleAddHero(array $get, $database): void
    {
        $uid = (int)$get['uid'];
        $user = $database->getUserArray($uid, 1);
        $capital = $database->getVrefCapital($uid);

        if (!$capital) {
            return;
        }

        $database->query(
            "INSERT INTO " . TB_PREFIX . "hero " .
            "(`uid`, `wref`, `regeneration`, `unit`, `name`, `level`, `points`, `experience`, " .
            "`dead`, `health`, `attack`, `defence`, `attackbonus`, `defencebonus`, `trainingtime`, " .
            "`autoregen`, `intraining`) VALUES (" .
            $uid . ", " . (int)$capital['wref'] . ", '0', " . (int)$get['u'] . ", " .
            "'" . addslashes($user['username']) . "', '0', '5', '0', '0', '100', '0', '0', '0', '0', " .
            "'" . time() . "', '50', '0')"
        );

        $database->query("UPDATE " . TB_PREFIX . "units SET hero = 1 WHERE vref = " . (int)$capital['wref']);

        header("Location: admin.php?p=player&uid=" . $uid . "&ac=1");
        exit;
    }

    /**
     * Dispatch a POST-triggered admin action (player deletion,
     * punishments, manual village creation).
     */
    public function Act2(array $post): void
    {
        global $admin;

        switch ($post['action'] ?? '') {

            case "DelPlayer":
                if ($admin->DelPlayer($post['uid'], $post['pass'])) {
                    header("Location: ?p=search&msg=ursdel");
                } else {
                    die('Invalid Admin password, cannot delete player. Please go back and retry.');
                }
                break;

            case "punish":
                $admin->Punish($post);
                $this->returnToCaller();
                break;

            case "addVillage":
                $admin->AddVillage($post);
                $this->returnToCaller();
                break;
        }
    }

    /** Authenticate an admin login attempt and start the ACP session on success. */
    public function LogIN(string $username, string $password): void
    {
        global $admin, $database;

        if (!$admin->Login($username, $password)) {
            echo "Error";
            return;
        }

        $_SESSION['admin_username'] = $username;
        $_SESSION['access'] = $database->getUserField($username, 'access', 1);
        $_SESSION['id'] = $database->getUserField($username, 'id', 1);

        $this->returnToCaller();
    }

    /** Clear the ACP session, effectively logging the admin out. */
    public function LogOut(): void
    {
        $_SESSION['access'] = '';
        $_SESSION['id'] = '';
    }

    /** Look up the display name for a building reference ID (used by village templates). */
    public function procResType($ref): string
    {
        return self::BUILDING_NAMES[$ref] ?? "Error";
    }
}

$funct = new funct();

if ($funct->CheckLogin()) {
    if (!empty($_GET['action'])) {
        $funct->Act($_GET);
    }
    if (!empty($_POST['action'])) {
        $funct->Act2($_POST);
    }
}

if (($_POST['action'] ?? '') === 'login') {
    $funct->LogIN($_POST['name'], $_POST['pw']);
}
