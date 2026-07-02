import json
import math
import textwrap
from html import escape
from pathlib import Path


ROOT = Path(__file__).resolve().parents[1]
DATA_FILE = ROOT / "data" / "articles.json"


PALETTES = {
    "Transformation": ("#fff6ed", "#221815", "#ff5a14", "#c99a5b", "#f6dac1"),
    "IA & Data": ("#f1fbff", "#10202b", "#0097a7", "#ff5a14", "#caeef5"),
    "Performance": ("#f7fbf3", "#172015", "#3f7d4b", "#ff5a14", "#d8ecd0"),
    "Coaching agile": ("#f6f4ff", "#171728", "#5b5bd6", "#ff5a14", "#dedbff"),
    "Leadership": ("#f3f8fb", "#101d25", "#1f6f8b", "#c99a5b", "#d8ebf4"),
    "Agilité": ("#f5fbf8", "#14221c", "#178a67", "#ff5a14", "#d5efe6"),
    "Agilité & DevOps": ("#f7f5ff", "#171425", "#6c4ddf", "#00a6a6", "#dfd7ff"),
    "Organisation & Agilité": ("#f6fbf7", "#142219", "#2f8f55", "#ff5a14", "#d7efdc"),
    "SEO & stratégie": ("#f4f8ff", "#121c2a", "#2e6bd9", "#c99a5b", "#dce8ff"),
    "Lectures": ("#fff8ee", "#201812", "#a66a2c", "#ff5a14", "#f3dfc3"),
}


SLUG_MOTIFS = {
    "transformation-5-leviers-valeur": "pillars",
    "ia-act-organisations-anticiper": "ai_shield",
    "ia-generative-passer-experimentation-impact": "ai_cards",
    "conduire-changement-dans-duree": "roadmap",
    "leviers-performance-durable": "levers",
    "pourquoi-coach-agile-organisation-agile": "team",
    "quotidien-scrum-master": "kanban",
    "plonger-profondeurs-leadership": "depth",
    "mode-produit-vs-mode-projet": "dual",
    "scrum-master-vs-chef-de-projet": "bridge",
    "manifestes-agiles-revolution-culturelle": "manifesto",
    "lean-management-et-scrum": "lean",
    "exemple-epic-user-stories-taches": "hierarchy",
    "maitrisez-velocite-agile": "velocity",
    "agile-devops-duo-gagnant": "devops",
    "comment-echouer-conduite-changement": "warning",
    "agilite-au-dela-des-methodes": "mindset",
    "seo-strategique-attirer-bon-trafic": "seo",
    "performance-durable-avantage-competitif": "horizon",
}


READING_HINTS = {
    "8-cles-gerer-stress-raphael-homat": "respiration",
    "performance-vie-jean-christophe-maisonneuve": "sommet",
    "culture-agile": "culture",
    "scrum-pratique-vivante-agilite": "scrum",
    "parole-sport-combat": "parole",
    "knowledge-management-entreprise": "memoire",
    "le-knowledge-management": "transmission",
}


def wrap_words(text, width=30, max_lines=4):
    lines = textwrap.wrap(text, width=width, break_long_words=False)
    if len(lines) > max_lines:
        lines = lines[:max_lines]
        lines[-1] = lines[-1].rstrip(" .,:;") + "..."
    return lines


def text_block(lines, x, y, size, color, weight=700, family="Inter, Arial, sans-serif", gap=1.18):
    out = []
    for i, line in enumerate(lines):
        out.append(
            f'<text x="{x}" y="{y + int(i * size * gap)}" fill="{color}" '
            f'font-family="{family}" font-size="{size}" font-weight="{weight}">{escape(line)}</text>'
        )
    return "\n".join(out)


def bubbles(accent, muted):
    return f"""
  <circle cx="1060" cy="120" r="148" fill="{accent}" opacity=".10"/>
  <circle cx="940" cy="570" r="190" fill="{muted}" opacity=".42"/>
  <circle cx="210" cy="570" r="150" fill="{accent}" opacity=".07"/>
  <path d="M45 515 C225 430 320 640 520 545 C705 455 805 490 1155 350" fill="none" stroke="{accent}" stroke-width="2" opacity=".18"/>
  <path d="M70 165 C230 95 320 285 480 210 C650 132 825 152 1115 88" fill="none" stroke="{muted}" stroke-width="2" opacity=".38"/>
"""


def motif_pillars(accent, accent2):
    rects = []
    heights = [190, 240, 150, 280, 215]
    for i, h in enumerate(heights):
        x = 705 + i * 72
        y = 450 - h
        rects.append(f'<rect x="{x}" y="{y}" width="46" height="{h}" rx="18" fill="{accent if i % 2 else accent2}" opacity="{0.72 if i % 2 else 0.52}"/>')
        rects.append(f'<circle cx="{x + 23}" cy="{y - 30}" r="18" fill="{accent}" opacity=".75"/>')
    return "\n".join(rects) + f'\n<path d="M728 420 L800 360 L872 405 L944 290 L1016 345" fill="none" stroke="{accent}" stroke-width="7" stroke-linecap="round" stroke-linejoin="round"/>'


def motif_ai_shield(accent, accent2):
    nodes = []
    for angle in range(0, 360, 45):
        x = 910 + math.cos(math.radians(angle)) * 150
        y = 335 + math.sin(math.radians(angle)) * 118
        nodes.append(f'<line x1="910" y1="335" x2="{x:.1f}" y2="{y:.1f}" stroke="{accent2}" stroke-width="3" opacity=".42"/>')
        nodes.append(f'<circle cx="{x:.1f}" cy="{y:.1f}" r="16" fill="{accent}" opacity=".8"/>')
    return f"""
  <path d="M910 170 L1050 225 L1024 405 C1006 500 960 548 910 575 C860 548 814 500 796 405 L770 225 Z" fill="#ffffff" opacity=".72" stroke="{accent}" stroke-width="6"/>
  {''.join(nodes)}
  <circle cx="910" cy="335" r="58" fill="{accent}" opacity=".86"/>
  <path d="M883 336 L904 357 L944 304" fill="none" stroke="#fffaf4" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"/>
"""


def motif_ai_cards(accent, accent2):
    return f"""
  <rect x="700" y="170" width="250" height="145" rx="26" fill="#ffffff" opacity=".78"/>
  <rect x="745" y="260" width="290" height="165" rx="28" fill="{accent2}" opacity=".56"/>
  <rect x="795" y="370" width="250" height="145" rx="26" fill="#ffffff" opacity=".86"/>
  <path d="M742 240 H890 M742 280 H840 M840 438 H990 M840 478 H940" stroke="{accent}" stroke-width="10" stroke-linecap="round"/>
  <path d="M700 525 C820 470 930 540 1070 455" fill="none" stroke="{accent}" stroke-width="9" stroke-linecap="round"/>
  <circle cx="1070" cy="455" r="22" fill="{accent}"/>
"""


def motif_roadmap(accent, accent2):
    dots = [(720, 470), (810, 360), (910, 405), (1000, 270), (1085, 300)]
    chunks = [f'<path d="M720 470 C790 365 840 345 910 405 S1012 275 1085 300" fill="none" stroke="{accent}" stroke-width="12" stroke-linecap="round"/>']
    for i, (x, y) in enumerate(dots, 1):
        chunks.append(f'<circle cx="{x}" cy="{y}" r="28" fill="#fffaf4" stroke="{accent}" stroke-width="7"/>')
        chunks.append(f'<text x="{x}" y="{y + 8}" text-anchor="middle" fill="{accent}" font-family="Inter, Arial, sans-serif" font-size="24" font-weight="900">{i}</text>')
    chunks.append(f'<rect x="765" y="145" width="250" height="82" rx="24" fill="{accent2}" opacity=".55"/>')
    return "\n".join(chunks)


def motif_levers(accent, accent2):
    out = []
    for i, y in enumerate([190, 275, 360, 445]):
        knob = 760 + i * 80
        out.append(f'<line x1="710" y1="{y}" x2="1060" y2="{y}" stroke="{accent2}" stroke-width="18" stroke-linecap="round" opacity=".58"/>')
        out.append(f'<circle cx="{knob}" cy="{y}" r="34" fill="{accent}" opacity=".86"/>')
    out.append(f'<path d="M725 545 A185 185 0 0 1 1080 545" fill="none" stroke="{accent}" stroke-width="12" stroke-linecap="round" opacity=".72"/>')
    return "\n".join(out)


def motif_team(accent, accent2):
    pts = [(885, 320), (780, 245), (1000, 245), (775, 430), (1005, 430)]
    lines = "".join(f'<line x1="885" y1="320" x2="{x}" y2="{y}" stroke="{accent2}" stroke-width="6" opacity=".48"/>' for x, y in pts[1:])
    circles = "".join(f'<circle cx="{x}" cy="{y}" r="{42 if i == 0 else 34}" fill="{accent if i == 0 else "#ffffff"}" stroke="{accent}" stroke-width="6" opacity=".9"/>' for i, (x, y) in enumerate(pts))
    return lines + circles


def motif_kanban(accent, accent2):
    cols = []
    for i, x in enumerate([700, 835, 970]):
        cols.append(f'<rect x="{x}" y="175" width="108" height="345" rx="22" fill="#ffffff" opacity=".72"/>')
        for j in range(3):
            y = 235 + j * 82 + (i % 2) * 12
            cols.append(f'<rect x="{x + 18}" y="{y}" width="72" height="44" rx="12" fill="{accent if (i+j)%2 else accent2}" opacity=".72"/>')
    return "\n".join(cols)


def motif_depth(accent, accent2):
    return f"""
  <path d="M690 470 C770 410 850 526 930 462 S1060 405 1110 455" fill="none" stroke="{accent}" stroke-width="11" stroke-linecap="round"/>
  <path d="M700 365 C790 315 850 420 940 365 S1065 310 1110 345" fill="none" stroke="{accent2}" stroke-width="8" stroke-linecap="round" opacity=".82"/>
  <path d="M725 255 C800 212 880 292 950 245 S1050 220 1100 260" fill="none" stroke="{accent}" stroke-width="5" stroke-linecap="round" opacity=".55"/>
  <circle cx="890" cy="340" r="70" fill="#ffffff" opacity=".62"/>
  <path d="M854 340 H926 M890 304 V376" stroke="{accent}" stroke-width="8" stroke-linecap="round"/>
"""


def motif_dual(accent, accent2):
    return f"""
  <rect x="690" y="170" width="195" height="350" rx="32" fill="#ffffff" opacity=".76"/>
  <rect x="920" y="170" width="195" height="350" rx="32" fill="{accent2}" opacity=".54"/>
  <path d="M735 255 H840 M735 330 H815 M735 405 H850" stroke="{accent}" stroke-width="12" stroke-linecap="round"/>
  <path d="M965 245 H1065 M965 320 H1035 M965 395 H1080" stroke="{accent}" stroke-width="12" stroke-linecap="round"/>
  <path d="M870 340 C900 305 910 305 940 340" fill="none" stroke="{accent}" stroke-width="9" stroke-linecap="round"/>
"""


def motif_bridge(accent, accent2):
    return f"""
  <circle cx="765" cy="310" r="72" fill="#ffffff" stroke="{accent}" stroke-width="7"/>
  <circle cx="1035" cy="310" r="72" fill="{accent2}" stroke="{accent}" stroke-width="7" opacity=".82"/>
  <path d="M835 310 C895 250 905 250 965 310" fill="none" stroke="{accent}" stroke-width="12" stroke-linecap="round"/>
  <path d="M735 455 H1065" stroke="{accent}" stroke-width="16" stroke-linecap="round"/>
  <path d="M780 455 L840 360 L900 455 L960 360 L1020 455" fill="none" stroke="{accent2}" stroke-width="8" stroke-linecap="round" stroke-linejoin="round"/>
"""


def motif_manifesto(accent, accent2):
    out = []
    for i, (x, y) in enumerate([(720, 175), (815, 220), (910, 165), (990, 245)]):
        out.append(f'<rect x="{x}" y="{y}" width="130" height="180" rx="18" fill="#ffffff" opacity=".78" transform="rotate({[-7,5,-4,8][i]} {x+65} {y+90})"/>')
        out.append(f'<path d="M{x+28} {y+55} H{x+105} M{x+28} {y+88} H{x+92} M{x+28} {y+121} H{x+100}" stroke="{accent}" stroke-width="8" stroke-linecap="round" opacity=".72"/>')
    out.append(f'<circle cx="1005" cy="470" r="58" fill="{accent2}" opacity=".8"/>')
    return "\n".join(out)


def motif_lean(accent, accent2):
    return f"""
  <path d="M830 225 C980 145 1110 250 1040 355 C970 460 785 425 760 330 C735 235 835 190 925 210" fill="none" stroke="{accent}" stroke-width="16" stroke-linecap="round"/>
  <path d="M1028 350 L1075 344 L1052 388" fill="none" stroke="{accent}" stroke-width="16" stroke-linecap="round" stroke-linejoin="round"/>
  <rect x="720" y="455" width="105" height="58" rx="16" fill="#ffffff" opacity=".82"/>
  <rect x="850" y="455" width="105" height="58" rx="16" fill="{accent2}" opacity=".66"/>
  <rect x="980" y="455" width="105" height="58" rx="16" fill="#ffffff" opacity=".82"/>
"""


def motif_hierarchy(accent, accent2):
    boxes = [(905, 160, 160), (760, 310, 130), (930, 310, 130), (705, 455, 110), (840, 455, 110), (975, 455, 110)]
    out = [f'<line x1="985" y1="240" x2="985" y2="310" stroke="{accent}" stroke-width="7" opacity=".65"/>']
    out += [f'<line x1="825" y1="390" x2="{x+55}" y2="455" stroke="{accent2}" stroke-width="6" opacity=".65"/>' for x in [705, 840]]
    out += [f'<line x1="995" y1="390" x2="{x+55}" y2="455" stroke="{accent2}" stroke-width="6" opacity=".65"/>' for x in [975]]
    for i, (x, y, w) in enumerate(boxes):
        out.append(f'<rect x="{x}" y="{y}" width="{w}" height="70" rx="18" fill="{accent if i == 0 else "#ffffff"}" opacity="{0.88 if i == 0 else 0.78}"/>')
    return "\n".join(out)


def motif_velocity(accent, accent2):
    bars = []
    for i, h in enumerate([80, 122, 92, 170, 145, 215]):
        bars.append(f'<rect x="{705+i*62}" y="{505-h}" width="38" height="{h}" rx="14" fill="{accent if i % 2 else accent2}" opacity=".75"/>')
    return "\n".join(bars) + f'\n<path d="M700 230 C790 350 880 170 1085 245" fill="none" stroke="{accent}" stroke-width="13" stroke-linecap="round"/><path d="M1048 220 L1090 246 L1044 268" fill="none" stroke="{accent}" stroke-width="13" stroke-linecap="round" stroke-linejoin="round"/>'


def motif_devops(accent, accent2):
    return f"""
  <path d="M785 335 C785 245 910 245 910 335 C910 425 785 425 785 335 Z" fill="none" stroke="{accent}" stroke-width="16"/>
  <path d="M910 335 C910 245 1035 245 1035 335 C1035 425 910 425 910 335 Z" fill="none" stroke="{accent2}" stroke-width="16"/>
  <circle cx="785" cy="335" r="18" fill="{accent}"/>
  <circle cx="1035" cy="335" r="18" fill="{accent2}"/>
  <path d="M750 505 H1070" stroke="{accent}" stroke-width="11" stroke-linecap="round" opacity=".65"/>
"""


def motif_warning(accent, accent2):
    return f"""
  <path d="M900 165 L1115 535 H685 Z" fill="#ffffff" opacity=".72" stroke="{accent}" stroke-width="8" stroke-linejoin="round"/>
  <path d="M900 280 V395" stroke="{accent}" stroke-width="18" stroke-linecap="round"/>
  <circle cx="900" cy="452" r="16" fill="{accent}"/>
  <path d="M710 540 C820 455 890 570 1035 460" fill="none" stroke="{accent2}" stroke-width="8" stroke-linecap="round" opacity=".72"/>
"""


def motif_mindset(accent, accent2):
    return f"""
  <circle cx="900" cy="335" r="150" fill="#ffffff" opacity=".72"/>
  <path d="M820 350 C760 255 855 205 902 270 C950 205 1045 255 985 350 C952 404 908 442 902 448 C896 442 852 404 820 350 Z" fill="{accent2}" opacity=".65"/>
  <path d="M735 505 C820 470 885 525 970 485 C1015 463 1055 448 1100 455" fill="none" stroke="{accent}" stroke-width="9" stroke-linecap="round"/>
"""


def motif_seo(accent, accent2):
    return f"""
  <circle cx="845" cy="295" r="92" fill="#ffffff" opacity=".78" stroke="{accent}" stroke-width="13"/>
  <path d="M910 360 L1015 465" stroke="{accent}" stroke-width="18" stroke-linecap="round"/>
  <rect x="955" y="205" width="42" height="220" rx="15" fill="{accent2}" opacity=".65"/>
  <rect x="1025" y="285" width="42" height="140" rx="15" fill="{accent}" opacity=".72"/>
  <rect x="1095" y="245" width="42" height="180" rx="15" fill="{accent2}" opacity=".55"/>
"""


def motif_horizon(accent, accent2):
    return f"""
  <path d="M715 470 C800 315 990 315 1080 470" fill="none" stroke="{accent}" stroke-width="14" stroke-linecap="round"/>
  <path d="M720 470 H1090" stroke="{accent2}" stroke-width="9" stroke-linecap="round" opacity=".72"/>
  <circle cx="900" cy="330" r="62" fill="{accent}" opacity=".82"/>
  <path d="M760 245 C830 200 970 200 1040 245" fill="none" stroke="{accent2}" stroke-width="7" stroke-linecap="round" opacity=".65"/>
"""


def motif_book(accent, accent2, hint):
    mark = {
        "respiration": '<path d="M892 310 C850 270 850 220 892 190 C934 220 934 270 892 310 Z" fill="{accent2}" opacity=".72"/>',
        "sommet": '<path d="M820 445 L900 245 L1010 445 Z" fill="{accent2}" opacity=".62"/><path d="M900 245 L940 340 L873 340 Z" fill="#fffaf4" opacity=".82"/>',
        "culture": '<circle cx="910" cy="305" r="92" fill="{accent2}" opacity=".62"/><path d="M820 305 H1000 M910 215 V395" stroke="{accent}" stroke-width="8" stroke-linecap="round"/>',
        "scrum": '<rect x="820" y="245" width="190" height="130" rx="24" fill="{accent2}" opacity=".58"/><path d="M850 290 H980 M850 330 H940" stroke="{accent}" stroke-width="10" stroke-linecap="round"/>',
        "parole": '<path d="M795 270 Q910 185 1025 270 V395 Q910 335 795 395 Z" fill="{accent2}" opacity=".66"/><circle cx="910" cy="300" r="22" fill="{accent}"/>',
        "memoire": '<circle cx="850" cy="300" r="34" fill="{accent2}" opacity=".85"/><circle cx="940" cy="245" r="34" fill="{accent}" opacity=".72"/><circle cx="1015" cy="345" r="34" fill="{accent2}" opacity=".85"/><path d="M880 292 L914 258 M965 270 L995 325" stroke="{accent}" stroke-width="8" stroke-linecap="round"/>',
        "transmission": '<path d="M790 325 C870 250 945 250 1030 325 C945 400 870 400 790 325 Z" fill="{accent2}" opacity=".62"/><circle cx="910" cy="325" r="45" fill="{accent}" opacity=".78"/>',
    }.get(hint, '<circle cx="910" cy="310" r="80" fill="{accent2}" opacity=".62"/>')
    mark = mark.format(accent=accent, accent2=accent2)
    return f"""
  <rect x="730" y="165" width="260" height="360" rx="28" fill="#ffffff" opacity=".82"/>
  <path d="M768 180 H990 V525 H768 C728 500 710 475 710 440 V225 C710 197 730 180 768 180 Z" fill="{accent2}" opacity=".48"/>
  <path d="M768 180 V525" stroke="{accent}" stroke-width="8" opacity=".72"/>
  <path d="M805 465 H955 M805 500 H925" stroke="{accent}" stroke-width="8" stroke-linecap="round" opacity=".58"/>
  {mark}
"""


def choose_motif(article, accent, accent2):
    slug = article["slug"]
    if article.get("type") == "lecture" or article.get("category") == "Lectures":
        return motif_book(accent, accent2, READING_HINTS.get(slug, "lecture"))
    motif = SLUG_MOTIFS.get(slug, "pillars")
    return {
        "pillars": motif_pillars,
        "ai_shield": motif_ai_shield,
        "ai_cards": motif_ai_cards,
        "roadmap": motif_roadmap,
        "levers": motif_levers,
        "team": motif_team,
        "kanban": motif_kanban,
        "depth": motif_depth,
        "dual": motif_dual,
        "bridge": motif_bridge,
        "manifesto": motif_manifesto,
        "lean": motif_lean,
        "hierarchy": motif_hierarchy,
        "velocity": motif_velocity,
        "devops": motif_devops,
        "warning": motif_warning,
        "mindset": motif_mindset,
        "seo": motif_seo,
        "horizon": motif_horizon,
    }[motif](accent, accent2)


def svg_for(article):
    category = article.get("category", "Article")
    bg, dark, accent, accent2, muted = PALETTES.get(category, PALETTES["Transformation"])
    title = article.get("title", article["slug"])
    label = "Lecture" if article.get("type") == "lecture" or category == "Lectures" else category
    title_lines = wrap_words(title, width=26, max_lines=4)
    subtitle = article.get("excerpt", "")
    subtitle_lines = wrap_words(subtitle, width=46, max_lines=2)
    motif = choose_motif(article, accent, accent2)
    uid = article["slug"].replace("-", "_")
    label_width = min(420, max(220, len(label) * 14 + 76))
    return f"""<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" width="1200" height="675" viewBox="0 0 1200 675" role="img" aria-labelledby="title-{uid} desc-{uid}">
<title id="title-{uid}">{escape(title)}</title>
<desc id="desc-{uid}">Visuel editorial pour {escape(label)}.</desc>
<defs>
  <linearGradient id="bg-{uid}" x1="0" y1="0" x2="1" y2="1">
    <stop offset="0" stop-color="{bg}"/>
    <stop offset=".58" stop-color="#fffaf4"/>
    <stop offset="1" stop-color="{muted}"/>
  </linearGradient>
  <filter id="shadow-{uid}" x="-20%" y="-20%" width="140%" height="140%">
    <feDropShadow dx="0" dy="24" stdDeviation="28" flood-color="#2c2014" flood-opacity=".13"/>
  </filter>
</defs>
<rect width="1200" height="675" fill="url(#bg-{uid})"/>
{bubbles(accent, muted)}
<rect x="38" y="38" width="1124" height="599" rx="46" fill="#ffffff" opacity=".64" filter="url(#shadow-{uid})"/>
<rect x="82" y="86" width="{label_width}" height="42" rx="21" fill="{accent}" opacity=".95"/>
<text x="110" y="114" fill="#fffaf4" font-family="Inter, Arial, sans-serif" font-size="16" font-weight="900" letter-spacing="1.6">{escape(label.upper())}</text>
<text x="82" y="177" fill="{accent}" font-family="Inter, Arial, sans-serif" font-size="18" font-weight="900" letter-spacing="2">{escape(article.get("read_time", "5 min").upper())}</text>
{text_block(title_lines, 82, 255, 48 if len(title_lines) < 4 else 42, dark, 800, "Playfair Display, Georgia, serif", 1.12)}
{text_block(subtitle_lines, 86, 505, 22, "#51473e", 600, "Inter, Arial, sans-serif", 1.35)}
<text x="86" y="592" fill="{dark}" opacity=".72" font-family="Inter, Arial, sans-serif" font-size="15" font-weight="800">Cédrick Benittah · Transformation, IA &amp; performance</text>
<g>
{motif}
</g>
</svg>
"""


def main():
    articles = json.loads(DATA_FILE.read_text(encoding="utf-8"))
    written = []
    for article in articles:
        image = article.get("image", "")
        if not image.startswith("/assets/img/articles/") or not image.endswith(".svg"):
            continue
        target = ROOT / image.lstrip("/")
        target.parent.mkdir(parents=True, exist_ok=True)
        target.write_text(svg_for(article), encoding="utf-8", newline="\n")
        written.append(target.relative_to(ROOT).as_posix())
    print(f"Generated {len(written)} article visuals")
    for item in written:
        print(item)


if __name__ == "__main__":
    main()
