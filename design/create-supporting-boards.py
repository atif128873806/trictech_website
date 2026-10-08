"""Generate editable SVG UX handoff boards for the Tricetech Figma file."""

from pathlib import Path
from xml.sax.saxutils import escape

OUT = Path(__file__).parent
RED = "#AA1E2B"
INK = "#1E252B"
TEXT = "#53616B"
RULE = "#D9DFE3"
SURFACE = "#F5F6F7"
WHITE = "#FFFFFF"


class Board:
    def __init__(self, width, height):
        self.parts = [f'<svg xmlns="http://www.w3.org/2000/svg" width="{width}" height="{height}" viewBox="0 0 {width} {height}">', f'<rect width="{width}" height="{height}" fill="{WHITE}"/>']

    def rect(self, x, y, w, h, fill=WHITE, stroke=None):
        self.parts.append(f'<rect x="{x}" y="{y}" width="{w}" height="{h}" fill="{fill}"' + (f' stroke="{stroke}"' if stroke else '') + '/>')

    def rule(self, x1, y1, x2, y2, color=RULE, width=1):
        self.parts.append(f'<line x1="{x1}" y1="{y1}" x2="{x2}" y2="{y2}" stroke="{color}" stroke-width="{width}"/>')

    def text(self, x, y, value, size=16, color=INK, weight=400):
        self.parts.append(f'<text x="{x}" y="{y}" fill="{color}" font-family="IBM Plex Sans, Noto Sans, Arial, sans-serif" font-size="{size}" font-weight="{weight}">{escape(value)}</text>')

    def lines(self, x, y, values, size=16, color=TEXT, weight=400, leading=25):
        for i, value in enumerate(values):
            self.text(x, y + i * leading, value, size, color, weight)

    def save(self, name):
        (OUT / name).write_text(''.join(self.parts) + '</svg>', encoding='utf-8')


# The one-page map also records where an inquiry route is available.
map_board = Board(1280, 760)
map_board.text(64, 82, 'Site map and visitor journeys', 34, INK, 700)
map_board.text(64, 116, 'Four destinations; direct service discovery and a clear route to a technical discussion.', 17, TEXT)
for x, title, body in [
    (64, 'Company / Home', 'Identity · two service paths · inquiry'),
    (464, 'Infrastructure & Networks', 'Nine approved service areas'),
    (864, 'Cloud & Software', 'Four approved service areas'),
]:
    map_board.rect(x, 187, 352, 126, WHITE, RULE)
    map_board.rect(x, 187, 4, 126, RED)
    map_board.text(x + 24, 231, title, 21, INK, 600)
    map_board.text(x + 24, 269, body, 15, TEXT)
map_board.rule(240, 313, 240, 387)
map_board.rule(640, 313, 640, 387)
map_board.rule(1040, 313, 1040, 387)
map_board.rule(240, 387, 1040, 387)
map_board.rule(640, 387, 640, 428)
map_board.rect(464, 428, 352, 112, SURFACE)
map_board.text(488, 473, 'Architecture Discussion', 21, INK, 600)
map_board.text(488, 507, 'Email · optional interest · message', 15, TEXT)
map_board.text(64, 620, '01  Service discovery', 18, RED, 600)
map_board.text(64, 655, 'Home  →  service family  →  technical discussion', 17, INK)
map_board.text(664, 620, '02  Direct inquiry', 18, RED, 600)
map_board.text(664, 655, 'Home or service page  →  form  →  feedback', 17, INK)
map_board.save('site-map-journeys.svg')


wire = Board(1280, 830)
wire.text(50, 63, 'Layout wireframes', 32, INK, 700)
wire.text(50, 92, 'Reading order, section hierarchy and contact route before visual styling.', 16, TEXT)
for x, label in [(50, '01 Company / Home'), (355, '02 Infrastructure'), (660, '03 Cloud & Software'), (965, '04 Inquiry')]:
    wire.text(x, 152, label, 18, INK, 600)
    wire.rect(x, 170, 260, 562, WHITE, RULE)
    wire.rect(x + 16, 188, 228, 28, SURFACE)
    wire.text(x + 26, 208, 'HEADER', 12, TEXT, 600)
wire.rect(66, 233, 228, 162, SURFACE); wire.text(82, 265, 'VALUE / COMPANY', 13, TEXT, 600)
wire.rect(66, 410, 228, 90, WHITE, RULE); wire.text(82, 442, 'OVERVIEW', 13, TEXT, 600)
wire.rect(66, 516, 228, 80, WHITE, RULE); wire.text(82, 545, 'SERVICE PATHS', 13, TEXT, 600)
wire.rect(66, 612, 228, 104, WHITE, RULE); wire.text(82, 640, 'INQUIRY', 13, TEXT, 600)
wire.rect(371, 233, 228, 88, SURFACE); wire.text(387, 265, 'SERVICE INTRO', 13, TEXT, 600)
for y in (336, 380, 424, 468, 512, 556, 600, 644, 688):
    wire.rect(371, y, 228, 30, WHITE, RULE)
wire.rect(676, 233, 228, 88, SURFACE); wire.text(692, 265, 'SERVICE INTRO', 13, TEXT, 600)
for y in (344, 436, 528, 620):
    wire.rect(676, y, 228, 76, WHITE, RULE)
wire.rect(981, 233, 228, 104, SURFACE); wire.text(997, 265, 'CONTACT ROUTES', 13, TEXT, 600)
for y, label in [(355, 'EMAIL'), (444, 'OPTIONAL INTEREST'), (533, 'MESSAGE')]:
    wire.rect(981, y, 228, 72, WHITE, RULE); wire.text(997, y + 27, label, 13, TEXT, 600)
wire.rect(981, 631, 228, 48, SURFACE); wire.text(997, 661, 'FEEDBACK', 13, TEXT, 600)
wire.save('wireframes.svg')


tablet = Board(768, 2350)
tablet.rect(0, 0, 768, 78)
tablet.text(40, 43, 'TRICETECH', 17, INK, 700)
tablet.text(40, 59, 'SOLUTIONS', 10, RED, 600)
tablet.text(460, 48, 'Services', 15, INK, 600)
tablet.rect(560, 18, 168, 44, RED); tablet.text(576, 46, 'Request discussion', 14, WHITE, 600)
tablet.rule(0, 77, 768, 77)
tablet.rect(0, 78, 768, 490, SURFACE)
tablet.text(40, 142, 'CANADIAN ENTERPRISE IT', 12, RED, 600)
tablet.lines(40, 208, ['Secure infrastructure.', 'Practical cloud. Software', 'built around your work.'], 42, INK, 700, 52)
tablet.lines(40, 405, ['Tricetech Solutions designs and supports enterprise environments', 'for Canadian organizations, from networks and protection to', 'private cloud and business applications.'], 17, TEXT, 400, 26)
tablet.rect(40, 475, 204, 52, RED); tablet.text(62, 508, 'Request a discussion', 15, WHITE, 600)
tablet.text(40, 664, 'WHO WE ARE', 12, RED, 600)
tablet.lines(40, 722, ['Built around your business.', 'Supported for the long term.'], 31, INK, 700, 40)
tablet.lines(40, 847, ['A Canadian IT services firm helping established organizations build', 'secure and reliable environments. We begin with business needs', 'and support the right solution over time.'], 16, TEXT, 400, 27)
tablet.rule(40, 988, 728, 988)
tablet.text(40, 1033, 'ECOSYSTEM CAPABILITIES', 12, RED, 600)
tablet.text(40, 1074, 'KVM · Ceph · Hyper-V · Red Hat · Windows · Oracle APEX · MySQL', 16, INK)
tablet.text(40, 1113, 'CrowdStrike · Sophos · Imunify360', 16, INK)
tablet.text(40, 1240, 'WHAT WE DO', 12, RED, 600)
tablet.text(40, 1297, 'Services for critical environments', 31, INK, 700)
for y, number, title, summary in [(1350, '01', 'Infrastructure & Networks', 'Networks, Microsoft systems, monitoring and protection.'), (1510, '02', 'Cloud & Software', 'Managed compute, storage, applications and migration.')]:
    tablet.rule(40, y, 728, y); tablet.text(40, y + 55, number, 18, RED, 600)
    tablet.text(100, y + 55, title, 24, INK, 600); tablet.text(100, y + 92, summary, 16, TEXT)
    tablet.text(100, y + 126, 'Explore service  →', 15, RED, 600)
tablet.rect(0, 1715, 768, 520, SURFACE)
tablet.text(40, 1772, 'TECHNICAL DISCOVERY', 12, RED, 600)
tablet.text(40, 1825, 'Start an architecture discussion.', 31, INK, 700)
tablet.text(40, 1870, '+1 905-624-6400  ·  info@tricetech.ca', 16, RED, 600)
tablet.text(40, 1935, 'Corporate email address *', 14, INK, 600); tablet.rect(40, 1951, 688, 44, WHITE, RULE)
tablet.text(40, 2030, 'Service interest (optional)', 14, INK, 600); tablet.rect(40, 2046, 688, 44, WHITE, RULE)
tablet.text(40, 2125, 'Technical description *', 14, INK, 600); tablet.rect(40, 2141, 688, 75, WHITE, RULE)
tablet.text(40, 2293, 'TRICETECH SOLUTIONS  ·  Infrastructure & Networks  ·  Cloud & Software', 14, TEXT)
tablet.save('home-tablet.svg')


menu = Board(390, 640)
menu.text(24, 38, 'TRICETECH', 16, INK, 700); menu.text(24, 55, 'SOLUTIONS', 10, RED, 600)
menu.rect(310, 13, 56, 44, WHITE, INK); menu.text(327, 41, 'Close', 12, INK, 600)
menu.rule(0, 71, 390, 71)
menu.text(24, 128, 'NAVIGATION', 11, RED, 600)
for y, label in [(165, 'Company'), (243, 'Infrastructure & Networks'), (321, 'Cloud & Software')]:
    menu.rule(24, y + 29, 366, y + 29)
    menu.text(24, y, label, 21, INK, 600)
menu.rect(24, 392, 342, 52, RED); menu.text(47, 425, 'Request a discussion', 16, WHITE, 600)
menu.text(24, 512, 'Focus moves to the first link on open.', 14, TEXT)
menu.text(24, 540, 'Escape closes; focus returns to Menu.', 14, TEXT)
menu.text(24, 568, 'The page beneath is unavailable while open.', 14, TEXT)
menu.save('mobile-menu.svg')


feedback = Board(1020, 690)
feedback.text(50, 66, 'Success and keyboard focus', 30, INK, 700)
feedback.text(50, 97, 'A restrained response, with clear next steps and visible keyboard location.', 15, TEXT)
feedback.rect(50, 135, 440, 470, WHITE, RULE)
feedback.text(78, 190, 'Inquiry received', 24, INK, 600)
feedback.lines(78, 241, ['Your architecture request has been submitted.', 'Our team will review the details you provided.'], 16, TEXT, 400, 25)
feedback.rect(78, 332, 384, 106, SURFACE)
feedback.text(96, 373, 'You may also contact us directly:', 15, INK, 600)
feedback.text(96, 408, '+1 905-624-6400 · info@tricetech.ca', 14, RED, 600)
feedback.text(78, 515, 'Return to services  →', 16, RED, 600)
feedback.rect(530, 135, 440, 470, WHITE, RULE)
feedback.text(558, 190, 'Visible keyboard focus', 24, INK, 600)
feedback.text(558, 241, 'Service interest (optional)', 14, INK, 600)
feedback.rect(556, 259, 388, 56, WHITE, RED)
feedback.rect(558, 261, 384, 52, WHITE, RULE)
feedback.text(574, 294, 'Choose an area', 15, INK)
feedback.text(558, 373, 'Focus uses a 2 px red outer outline.', 15, TEXT)
feedback.text(558, 402, 'Labels remain visible; error text explains the fix.', 15, TEXT)
feedback.text(558, 431, 'Red is never the only state cue.', 15, TEXT)
feedback.save('inquiry-success-focus.svg')

print('Wrote five supporting boards')
