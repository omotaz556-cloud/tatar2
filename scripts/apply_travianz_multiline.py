#!/usr/bin/env python3
"""Apply TravianZ Arabic for all tz_def keys including multiline; then patch Novaterra-only strings."""
import re
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
REF_AR = Path(
    r"C:\Users\menam\.cursor\projects\c-Users-menam-OneDrive-Desktop-tatar"
    r"\_travianz_ref\GameEngine\Lang\ar.php"
)
AR = ROOT / "GameEngine" / "Lang" / "ar.php"

TZ_DEF_RE = re.compile(
    r"tz_def\s*\(\s*'([^']+)'\s*,\s*(.+?)\s*\)\s*;",
    re.MULTILINE | re.DOTALL,
)

# Novaterra-only keys: Arabic translations (not in TravianZ or need custom branding)
NOVATERRA_AR: dict[str, str] = {
    "GK_COORD_X": "X",
    "GK_COORD_Y": "Y",
    "HR": "س.",
    "TZ_NAV_COUNT_REPORT": "تقرير",
    "TZ_NAV_COUNT_MESSAGE": "رسالة",
    "SENT_AS_SUP": "أُرسلت كدعم",
    "SENT_AS_MH": "أُرسلت كصياد متعدد",
    "TZ_RALLY_SEND_TROOPS": "إرسال القوات",
    "TZ_RALLY_FARMS": "المزارع",
    "TZ_RALLY_IN_VILLAGE": "في القرية",
    "TZ_RALLY_INCOMING": "قادمة",
    "TZ_RALLY_OUTGOING": "مغادرة",
    "TZ_RALLY_IN_OTHER": "في قرى أخرى",
    "TZ_PLUS_CLUB": "نادي البلس",
    "TZ_INDEX_NEWS_TUTORIALS": "الأخبار والدروس",
    "TZ_INDEX_SERVERS": "عدد الخوادم",
    "TZ_INDEX_SERVERS_COUNT": "1",
    "TZ_INDEX_SCREENSHOTS": "لقطات من اللعبة",
    "TZ_INDEX_NAV_REGISTER": "تسجيل",
    "TZ_INDEX_NAV_LOGIN": "دخول",
    "TZ_INDEX_GAME_TITLE": "حرب الإغريق",
    "TZ_INDEX_BROWSER_GAME": "لعبة متصفح",
    "TZ_BATTLE_SIMULATOR": "محاكي المعارك",
    "TZ_A2B_SEND_COL": "إرسال",
    "TZ_A2B_AVAILABLE": "متاح",
    "TZ_A2B_LAST_SEND": "آخر إرسال",
    "TZ_A2B_TARGET_INFO": "معلومات الإرسال",
    "TZ_VILLAGE_NAME_LABEL": "اسم القرية",
    "TZ_OR_COORDINATES_LABEL": "أو الإحداثيات",
    "TZ_FULL_ATTACK": "هجوم كامل",
    "TZ_RAID_ATTACK": "هجوم مداهمة",
    "MERCHANT_ONE": "تاجر",
    "MERCHANTS_COUNT_NOW": "عدد التجار الآن:",
    "MERCHANTS_COUNT_AT_LEVEL": "عدد التجار في المستوى",
    "NPC_TRADE_MISMATCH": "تعذّر إتمام التبادل. وزّع الموارد حتى يصبح المتبقي 0 ثم أعد المحاولة.",
    "NPC_TRADE_CAPACITY": "لا يوجد مساحة كافية في المستودع/الصومعة لكل الموارد. قلّل المبالغ أو رقّي المخازن.",
    "TRAINING_DURATION_NOW": "مدة التدريب الآن:",
    "TRAINING_DURATION_AT_LEVEL": "مدة التدريب في المستوى",
    "OASIS_EXPAND_POP": "1020",
    "GENDER0": "غ/م",
    "MALE0": "ذ",
    "FEMALE0": "أ",
    "PREF_NAME_SLOT": "%d/%d",
    "PREF_GO_TO_MAP": "الذهاب إلى الخريطة",
    "PLUS_LOGO": '<b><font color="#71D000">P</font><font color="#FF6F0F">l</font><font color="#71D000">u</font><font color="#FF6F0F">s</font></b>',
    "TZ_MSG_NEW": "(جديد)",
    "TZ_MSG_UNREAD": "(غير مقروء)",
    "TZ_MSG_REPLY_PREFIX": "رد:",
    "TZ_MSG_IGNORED_TITLE": "اللاعبون المتجاهَلون",
    "TZ_NO_IGNORED_PLAYERS": "لا يوجد لاعبون متجاهَلون.",
    "TZ_NO_FARMS_MUST_ADD": "لا توجد مزارع؛ يجب إضافة مزرعة.",
    "TZ_ADD_FARM": "إضافة مزرعة",
    "TZ_FARMS_ATTACK_ADD": "هجوم | إضافة مزرعة",
    "TZ_FARM_BEGINNER_PROT": "اللاعب تحت حماية المبتدئين.",
    "TZ_FARM_COORDS": "الإحداثيات",
    "TZ_FARM_COORDS_INVALID": "أدخل إحداثيات صالحة.",
    "TZ_FARM_ENTER_COORDS": "أدخل الإحداثيات.",
    "TZ_FARM_LIST_CREATE_FAIL": "تعذّر إنشاء قائمة المزارع.",
    "TZ_FARM_LIST_INVALID": "قائمة مزارع غير صالحة.",
    "TZ_FARM_NO_TROOPS": "يجب اختيار نوع قوات واحد على الأقل.",
    "TZ_FARM_NO_VILLAGE": "لا توجد قرية في هذه الإحداثيات.",
    "TZ_FARM_SAME_VILLAGE": "لا يمكنك مهاجمة القرية التي ترسل منها القوات.",
    "TZ_FARM_TROOP_COUNT": "عدد القوات",
    "TZ_FARM_TROOP_TYPE": "نوع القوات",
    "TZ_COPIED": "تم نسخ الرابط.",
    "TZ_RAGEZONE_COM": "RageZone.com",
    "TZ_RPT_ATTACKER_SHORT": "المهاجم",
    "TZ_RPT_ATTACKS_ON_VILLAGE": "يهاجم القرية",
    "TZ_RPT_DEFENDER_SHORT": "المدافع",
    "TZ_RPT_HOUR_ABBR": "س",
    "TZ_RPT_LINK": "رابط التقرير",
    "TZ_RPT_MIN_ABBR": "د",
    "TZ_RPT_NATURE_LEGACY_HINT": "أعداد القوات تعرض الحيوانات في الواحة حالياً. أعد الهجوم لمعرفة تفاصيل المعركة والخسائر.",
    "TZ_RPT_NO_DEF_INTEL": "لا تتوفر معلومات قوات المدافع لأن جميع المهاجمين قُتلوا.",
    "TZ_RPT_RESOURCES_ROW": "الموارد",
    "TZ_RPT_SCOUTS_ON_VILLAGE": "يراقب القرية",
    "TZ_RPT_TRAVEL": "السفر",
    "TZ_RPT_TYPES": "الأنواع",
    "TZ_RPT_WORLD_DAY": "يوم",
    "WW_V_M": "قرية أعجوبة العالم الرسمية",
    "SPIELREGELN": "قواعد اللعبة",
    "FAQ": "الأسئلة الشائعة",
    "BANNED": "محظور",
    "PEACE": "سلام",
    "GOLD_ON": "مفعّل",
    "HOURS_OF_BG_PROT": "ساعات متبقية من حماية المبتدئين",
    "PLAYER_HAS": "هذا اللاعب لديه",
    "PLAYER_WAS_REG_ON": "سجّل هذا اللاعب حسابه في",
    "NATARS_ACC": "حساب الطرطور الرسمي",
    "ADM_HOME_PHP_MYSQL": "PHP / MySQL",
    "ADM_QTYP_37": "qtyp&nbsp;37",
    "PUBLIC_RULES_DEFAMATORY": "المشاركة في لغة مسيئة، تشهيرية، عنصرية، جنسية أو بذيئة؛ التحقير من أي دين، عرق، أمة، جنس، فئة عمرية أو توجه؛ التهديد بأفعال في الحياة الواقعية.",
    "PUBLIC_RULES_DISPLAY_REPORTS": "عرض تقارير المعارك أو الرسائل علناً دون موافقة الأطراف المعنية.",
    "PUBLIC_RULES_IMPERSONATION": "انتحال صفة مسؤولين أو مناصب رسمية بأي شكل غير قانوني.",
    "PUBLIC_RULES_NO_POLITICS": "لا يُسمح بسياسة العالم الواقعي في الأسماء والرسائل والأوصاف.",
    "PUBLIC_RULES_SECTION_10": "§10 قواعد الخادم والعقوبات",
    "PUBLIC_RULES_SECTION_10_BODY": "قبل تطبيق أي عقوبة، يجب نشر قواعد الخادم الحالية والسلوك المحظور ومستويات العقوبة بوضوح في هذه الصفحة. يمكن للاعبين مراجعة القواعد وتقديم استئناف عبر الدعم.",
    "PUBLIC_RULES_SECTION_1_1": "§1.1 التسجيل",
    "PUBLIC_RULES_SECTION_1_4": "§1.4 تبديل الحسابات",
    "PUBLIC_RULES_SECTION_2": "§2 الجلوس واستخدام نفس الحاسوب",
    "PUBLIC_RULES_SECTION_2_1": "§2.1 الجلوس",
    "PUBLIC_RULES_SECTION_2_2": "§2.2 استخدام نفس الحاسوب",
    "PUBLIC_RULES_SECTION_3": "§3 استخدام البرامج الخارجية",
    "PUBLIC_RULES_SECTION_5": "§5 معاملات مالية",
    "PUBLIC_RULES_SECTION_6": "§6 آداب الشبكة",
    "PUBLIC_RULES_SECTION_7": "§7 العقوبات",
    "PUBLIC_RULES_SECTION_9": "§9 بند التصحيح",
    "PUBLIC_RULES_UNDERAGE": "نشر أو إرسال أي مادة غير مناسبة للقاصرين.",
}


def parse_ref() -> dict[str, str]:
    content = REF_AR.read_text(encoding="utf-8", errors="replace")
    return {m.group(1): m.group(2).strip() for m in TZ_DEF_RE.finditer(content)}


def apply():
    ref = parse_ref()
    content = AR.read_text(encoding="utf-8", errors="replace")
    updated_ref = 0
    updated_nv = 0

    def repl(m):
        nonlocal updated_ref
        key = m.group(1)
        if key in ref:
            new_rhs = ref[key]
            if m.group(2).strip() != new_rhs:
                updated_ref += 1
            return f"tz_def('{key}', {new_rhs});"
        return m.group(0)

    content = TZ_DEF_RE.sub(repl, content)

    for key, val in NOVATERRA_AR.items():
        escaped = val.replace("\\", "\\\\").replace("'", "\\'")
        pattern = re.compile(
            rf"tz_def\s*\(\s*'{re.escape(key)}'\s*,\s*.*?\s*\)\s*;",
            re.MULTILINE | re.DOTALL,
        )
        new_line = f"tz_def('{key}', '{escaped}');"
        if pattern.search(content):
            content = pattern.sub(new_line, content, count=1)
            updated_nv += 1

    AR.write_text(content, encoding="utf-8")
    print(f"Updated from TravianZ (multiline): {updated_ref}")
    print(f"Patched Novaterra-only keys: {updated_nv}")


if __name__ == "__main__":
    apply()
