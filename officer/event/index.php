<?php
ob_start(); // เปิดใช้งาน output buffering
session_start();
$currentDir = basename(dirname($_SERVER['PHP_SELF']));

require('../fpdf/fpdf.php');
require_once('../../config/database.php');

if (isset($_GET['cetak']) && isset($_GET['id'])) {
    $id = intval($_GET['id']); // ป้องกัน SQL Injection ด้วย intval()

    class MedicalForm extends FPDF
    {
        // หน้าหัวฟอร์ม
        function Header()
        {
            $this->AddFont('THSarabun', '', 'THSarabun.php');
            $this->AddFont('THSarabun', 'B', 'THSarabunB.php');

            $this->SetFont('THSarabun', 'B', 16);
            $this->Cell(0, 8, iconv('UTF-8', 'cp874', 'สำนักงานระบบบริการการแพทย์ฉุกเฉิน'), 0, 1, 'C');
            $this->Cell(0, 8, iconv('UTF-8', 'cp874', 'แบบบันทึกการปฏิบัติงานหน่วยปฏิบัติการการแพทย์ฉุกเฉินระดับพื้นฐาน'), 0, 1, 'C');
            $this->Ln(5);
        }

        function LoadData($pdo, $id)
        {
            $stmt = $pdo->prepare("SELECT * FROM incident_areas WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        // ฟังก์ชันสร้าง checkbox
        function CheckBox($x, $y, $size = 3, $checked = false)
        {
            $this->SetXY($x, $y);
            $this->Rect($x, $y, $size, $size);
            if ($checked) {
                $this->Line($x, $y, $x + $size, $y + $size);
                $this->Line($x + $size, $y, $x, $y + $size);
            }
        }

        // ฟังก์ชันสร้างเส้นใต้สำหรับกรอกข้อมูล
        function UnderLine($x, $y, $width)
        {
            $this->Line($x, $y + 3, $x + $width, $y + 3);
        }

        // Section 1: หน่วยบริการ
        function Section1($data)
        {
            $this->SetFont('THSarabun', 'B', 12);
            $this->Cell(50, 5, iconv('UTF-8', 'cp874', '1. หน่วยบริการ'), 0, 0);
            $this->Cell(70, 5, iconv('UTF-8', 'cp874', 'สำคัญของศูนย์:'), 0, 0);
            $this->Cell(0, 5, iconv('UTF-8', 'cp874', 'เลขที่ผู้ป่วย:'), 0, 1);

            // กรอบส่วนที่ 1
            $this->Rect(10, $this->GetY(), 190, 30);

            $this->SetFont('THSarabun', '', 11);
            $y_start = $this->GetY() + 3;

            // แถวที่ 2
            $y_start += 0;
            $this->SetXY(15, $y_start);
            $this->Cell(20, 5, iconv('UTF-8', 'cp874', 'ชื่อหน่วยบริการ:'), 0, 0);
            $this->Cell(35, 5, iconv('UTF-8', 'cp874', $data['unit_name']), 0, 0);

            $this->SetXY(100, $y_start);
            $this->Cell(15, 5, iconv('UTF-8', 'cp874', 'วัน/เดือน/ปี:'), 0, 0);
            $this->Cell(15, 5, iconv('UTF-8', 'cp874', $data['record_date']), 0, 0);

            $this->SetXY(140, $y_start);
            $this->Cell(15, 5, iconv('UTF-8', 'cp874', 'ปฏิบัติการที่:'), 0, 0);
            $this->Cell(15, 5, iconv('UTF-8', 'cp874', $data['operation_number']), 0, 0);

            // แปลงข้อมูลชื่อเจ้าหน้าที่เป็น array
            $staffRaw = $data['staff_members']; // เช่น "สมชาย,ปวีณา,นิกร,สุภา"
            $staff = array_map('trim', explode(',', $staffRaw)); // แยกชื่อและลบช่องว่าง

            // แถวที่ 3-4 (เจ้าหน้าที่)
            $y_start += 5;
            $this->SetXY(15, $y_start);
            $this->Cell(30, 5, iconv('UTF-8', 'cp874', 'เจ้าหน้าที่ผู้ปฏิบัติการ'), 0, 0);

            // คนที่ 1
            $this->Cell(5, 5, '1.', 0, 0);
            $this->Cell(40, 5, iconv('UTF-8', 'cp874', $staff[0] ?? ''), 0, 0);

            // คนที่ 2
            $this->SetXY(120, $y_start);
            $this->Cell(5, 5, '2.', 0, 0);
            $this->Cell(40, 5, iconv('UTF-8', 'cp874', $staff[1] ?? ''), 0, 0);

            // แถวล่าง (3,4)
            $y_start += 5;
            $this->SetXY(45, $y_start);
            $this->Cell(5, 5, '3.', 0, 0);
            $this->Cell(40, 5, iconv('UTF-8', 'cp874', $staff[2] ?? ''), 0, 0);

            $this->SetXY(120, $y_start);
            $this->Cell(5, 5, '4.', 0, 0);
            $this->Cell(40, 5, iconv('UTF-8', 'cp874', $staff[3] ?? ''), 0, 0);

            // ผลการปฏิบัติงาน
            $y_start += 5;
            // อ่านค่าจากฐานข้อมูล
            $operation_result = trim($data['operation_result']); // เช่น "พบเหตุ" หรือ "ไม่พบเหตุ"

            // วาดคำว่า "ผลการปฏิบัติงาน"
            $this->SetXY(15, $y_start);
            $this->Cell(35, 5, iconv('UTF-8', 'cp874', 'ผลการปฏิบัติงาน'), 0, 0);

            // CheckBox "ไม่พบเหตุ"
            $this->CheckBox(50, $y_start + 1, 3); // ช่องเปล่า
            if ($operation_result == 'ไม่พบเหตุ') {
                $this->SetXY(50, $y_start + 0.5);
                $this->SetFont('THSarabun', '', 12);
                $this->Cell(3, 3, iconv('UTF-8', 'cp874', '/'), 0, 0, 'C');
            }
            $this->SetXY(55, $y_start);
            $this->Cell(20, 5, iconv('UTF-8', 'cp874', 'ไม่พบเหตุ'), 0, 0);

            // CheckBox "พบเหตุ"
            $this->CheckBox(75, $y_start + 1, 3); // ช่องเปล่า
            if ($operation_result == 'พบเหตุ') {
                $this->SetXY(75, $y_start + 0.5);
                $this->SetFont('THSarabun', 'B', 20);
                $this->Cell(3, 2, iconv('UTF-8', 'cp874', '/'), 0, 0, 'C');
                $this->SetFont('THSarabun', '', 11);
            }
            $this->SetXY(80, $y_start);
            $this->Cell(40, 5, iconv('UTF-8', 'cp874', 'พบเหตุ'), 0, 0);

            $this->SetXY(88, $y_start);
            $this->Cell(40, 5, iconv('UTF-8', 'cp874', 'สถานที่เกิดเหตุ'), 0, 0);
            $this->UnderLine(105, $y_start, 90);

            $y_start += 5;
            $this->SetXY(45, $y_start);
            $this->Cell(35, 5, iconv('UTF-8', 'cp874', 'เหตุการณ์'), 0, 0);
            $this->UnderLine(57, $y_start, 138);

            $this->SetY($this->GetY() + 7);
        }

        // Section 2: ข้อมูลเวลา
        function Section2($data)
        {
            $this->SetFont('THSarabun', 'B', 12);
            $this->Cell(0, 6, iconv('UTF-8', 'cp874', '2. ข้อมูลเวลา'), 0, 1);

            // เริ่มตำแหน่ง
            $x_start = 10;
            $y_start = $this->GetY();
            $cell_height = 10;

            $col_widths = [24, 24, 24, 24, 24, 24, 23, 23];

            // ====== แถว 1-2: เวลา (น.)
            $headers = ['เวลา (น.)', 'รับแจ้ง', 'สั่งการ', 'ออกจากฐาน', 'ถึงที่เกิดเหตุ', 'ออกจากที่เกิดเหตุ', 'ถึง รพ.', 'กลับถึงฐาน'];
            $sub_values = ['09:15', '09:18', '09:20', '09:30', '09:40', '09:55', '10:20']; // ข้อความในแถวที่ 2

            $x = $x_start;
            $this->SetFont('THSarabun', 'B', 10);
            foreach ($headers as $i => $header) {
                if ($i == 0) {
                    // ช่อง "เวลา (น.)" ผสานแนวตั้ง
                    $this->Rect($x, $y_start, $col_widths[$i], $cell_height * 2);
                    $this->SetXY($x, $y_start + 6);
                    $this->MultiCell($col_widths[$i], 5, iconv('UTF-8', 'cp874', $header), 0, 'C');
                } else {
                    // วาดกรอบ
                    $this->Rect($x, $y_start, $col_widths[$i], $cell_height);
                    $this->Rect($x, $y_start + $cell_height, $col_widths[$i], $cell_height);

                    // หัวตาราง (แถวแรก)
                    $this->SetXY($x, $y_start + 2);
                    $this->MultiCell($col_widths[$i], 5, iconv('UTF-8', 'cp874', $header), 0, 'C');

                    // ข้อความแถวล่าง (แถวที่ 2)
                    $this->SetXY($x, $y_start + $cell_height + 2);
                    $this->Cell($col_widths[$i], 5, $sub_values[$i - 1], 0, 0, 'C');
                }

                $x += $col_widths[$i];
            }


            // ====== แถว 3-4: รวมเวลา
            $y = $y_start + $cell_height * 2;
            $x = $x_start;

            // รวมเวลา (แนวตั้ง 2 ช่อง)
            $this->SetFont('THSarabun', 'B', 10);
            $this->Rect($x, $y, $col_widths[0], $cell_height * 2);
            $this->SetXY($x, $y + 5);
            $this->Cell($col_widths[0], 5, iconv('UTF-8', 'cp874', 'รวมเวลา (นาที)'), 0, 0, 'C');
            $x += $col_widths[0];

            // Response time = ... นาที (ผสาน 4 ช่องแนวนอน)
            $w_resp = array_sum(array_slice($col_widths, 1, 4));
            $this->Rect($x, $y, $w_resp, $cell_height * 2);
            $this->SetXY($x + 2, $y + 5);
            $this->Cell(30, 5, 'Response time =', 0, 0);
            $this->UnderLine($x + 38, $y + 5, 25);
            $this->Cell(15, 5, iconv('UTF-8', 'cp874', 'นาที'), 0, 0);

            // สุ่มและใส่ค่า Response time (5-30 นาที)
            $resp_time = rand(5, 30);
            $this->SetXY($x + 38, $y + 5);
            $this->Cell(25, 5, $resp_time, 0, 0, 'C');

            $x += $w_resp;

            // นาที (แถวบน - รวม 2 ช่อง)
            $w_mid = $col_widths[5] + $col_widths[6];
            $this->Rect($x, $y, $w_mid, $cell_height); // วาดกรอบรวม 2 ช่อง
            $this->SetXY($x + 2, $y + 2);
            $this->Cell($w_mid - 4, 5, iconv('UTF-8', 'cp874', 'นาที'), 0, 0, 'L');
            $x += $w_mid;

            // ช่องว่างด้านขวา (แถวบน - คอลัมน์ที่ 7)
            $this->Rect($x, $y, $col_widths[7], $cell_height);

            // รีเซ็ต $x สำหรับแถวล่าง
            $x -= $w_mid;

            // ขยับเส้นแบ่งขวา 3 หน่วย
            $shift = -1;

            // ช่องว่าง (แถวล่าง - ช่องซ้าย 1 ช่อง) — ลดความกว้างลง
            $this->Rect($x, $y + $cell_height, $col_widths[7] - $shift, $cell_height);

            // ช่อง "นาที" (แถวล่าง - ผสาน 2 ช่องขวา) — เริ่มจากขวาและเพิ่มกว้าง
            $this->Rect($x + $col_widths[7] - $shift, $y + $cell_height, $w_mid + $shift, $cell_height);

            // ใส่ข้อความ "นาที"
            $this->SetXY($x + $col_widths[7] + 2, $y + $cell_height + 2);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'นาที'), 0, 0);


            // ====== แถว 5: เลข กม.
            $y += $cell_height * 2;
            $x = $x_start;

            $this->Rect($x, $y, $col_widths[0], $cell_height);
            $this->SetXY($x, $y + 2);
            $this->Cell($col_widths[0], 5, iconv('UTF-8', 'cp874', 'เลข กม.'), 0, 0, 'C');
            $x += $col_widths[0];

            // สุ่มและใส่เลข กม. ใน 4 ช่องหลัก
            $km_widths = [
                array_sum(array_slice($col_widths, 1, 3)), // ช่องที่ 1 (กว้าง 72)
                array_sum(array_slice($col_widths, 4, 2)), // ช่องที่ 2 (กว้าง 48)
                $col_widths[6],                           // ช่องที่ 3 (กว้าง 23)
                $col_widths[7]                            // ช่องที่ 4 (กว้าง 23)
            ];

            $km_values = [];
            for ($i = 0; $i < 4; $i++) {
                $km_values[$i] = number_format(rand(5, 50) / 10, 1); // สุ่ม 0.5 - 5.0 km
                $this->Rect($x, $y, $km_widths[$i], $cell_height);
                $this->SetXY($x, $y + 2);
                $this->Cell($km_widths[$i], 5, $km_values[$i], 0, 0, 'C');
                $x += $km_widths[$i];
            }

            // ====== แถว 6-7: ระยะทาง
            $y += $cell_height;
            $x = $x_start;

            $this->Rect($x, $y, $col_widths[0], $cell_height * 2);
            $this->SetXY($x, $y + 5);
            $this->Cell($col_widths[0], 5, iconv('UTF-8', 'cp874', 'ระยะทาง (กม.)'), 0, 0, 'C');
            $x += $col_widths[0];

            // รวมระยะทางไป (สุ่ม 1.0 - 20.0 km)
            $total_distance = number_format(rand(10, 200) / 10, 1);
            $this->Rect($x, $y, array_sum(array_slice($col_widths, 1, 4)), $cell_height * 2);
            $this->SetXY($x + 2, $y + 5);
            $this->Cell(35, 5, iconv('UTF-8', 'cp874', 'รวมระยะทางไป'), 0);
            $this->UnderLine($x + 40, $y + 5, 25);
            $this->SetXY($x + 40, $y + 5);
            $this->Cell(25, 5, $total_distance, 0, 0, 'C');
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'กม.'), 0);

            $x += array_sum(array_slice($col_widths, 1, 4));

            // ช่องแรก
            $this->Rect($x, $y, $col_widths[5], $cell_height);
            $this->SetXY($x + 1, $y + 2); // ขยับเล็กน้อยเพื่อเว้นขอบ
            $this->Cell($col_widths[5] - 2, 5, iconv('UTF-8', 'cp874', 'ข้อความ A'), 0, 0, 'L');

            // ช่องสอง (ถัดจากช่องแรก)
            $x2 = $x + $col_widths[5];
            $w2 = $col_widths[6] + $col_widths[7];

            $this->Rect($x2, $y, $w2, $cell_height);
            $this->SetXY($x2 + 1, $y + 2);
            $this->Cell($w2 - 2, 5, iconv('UTF-8', 'cp874', 'ข้อความ B'), 0, 0, 'L');


            // ระยะไป รพ. (สุ่ม 0.5 - 10.0 km)
            $hospital_distance = number_format(rand(5, 100) / 10, 1);
            $this->Rect($x, $y + $cell_height, $col_widths[5] + $col_widths[6], $cell_height);
            $this->Rect($x + $col_widths[5] + $col_widths[6], $y + $cell_height, $col_widths[7], $cell_height);
            $this->SetXY($x + $col_widths[5] + 2, $y + $cell_height + 2);
            $this->Cell(20, 5, iconv('UTF-8', 'cp874', 'ระยะไป รพ.'), 0);
            $this->UnderLine($x + $col_widths[5] + 27, $y + $cell_height + 2, 20);
            $this->SetXY($x + $col_widths[5] + 27, $y + $cell_height + 2);
            $this->Cell(20, 5, $hospital_distance, 0, 0, 'C');
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'กม.'), 0);

            // ยก Y ลงแถวถัดไป
            $this->SetY($y + $cell_height * 2 + 0);
        }

        // Section 3: ข้อมูลผู้ป่วย
        function Section3($data)
        {
            $this->SetFont('THSarabun', 'B', 12);
            $this->Cell(0, 6, iconv('UTF-8', 'cp874', '3. ข้อมูลผู้ป่วย'), 0, 1);

            $y_start = $this->GetY();
            $this->Rect(10, $y_start, 190, 105);

            $this->SetFont('THSarabun', '', 11);

            // ชื่อผู้ป่วย
            $y_start += -3;
            $this->SetXY(15, $y_start + 5);
            $this->Cell(20, 5, iconv('UTF-8', 'cp874', 'ชื่อผู้ป่วย:'), 0, 0);
            $this->UnderLine(25, $y_start + 5, 60);

            $this->SetXY(85, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'อายุ'), 0, 0);
            $this->UnderLine(91, $y_start + 5, 15);
            $this->SetXY(106, $y_start + 5);
            $this->Cell(50, 5, iconv('UTF-8', 'cp874', 'ปี'), 0, 0);
            $this->SetXY(110, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'เพศ'), 0, 0);

            $this->CheckBox(117, $y_start + 6, 3);
            $this->SetXY(120, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'ชาย'), 0, 0);
            $this->CheckBox(127, $y_start + 6, 3);
            $this->SetXY(130, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'หญิง'), 0, 0);

            // เลขบัตรประชาชน
            $y_start += 5;
            $this->SetXY(15, $y_start + 5);
            $this->Cell(20, 5, iconv('UTF-8', 'cp874', 'เลขบัตรประชาชน:'), 0, 0);
            $this->UnderLine(35, $y_start + 5, 100);

            // สิทธิการรักษา
            $y_start += 5;
            $this->SetXY(15, $y_start + 5);
            $this->Cell(20, 5, iconv('UTF-8', 'cp874', 'สิทธิการรักษา:'), 0, 0);
            $this->CheckBox(32, $y_start + 6, 3);
            $this->SetXY(35, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'บัตรทอง'), 0, 0);
            $this->CheckBox(46, $y_start + 6, 3);
            $this->SetXY(49, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'ข้าราชการ'), 0, 0);
            $this->CheckBox(62, $y_start + 6, 3);
            $this->SetXY(65, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'ประกันสังคม'), 0, 0);
            $this->CheckBox(81, $y_start + 6, 3);
            $this->SetXY(84, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'แรงงานต่างด้าวขึ้นทะเบียน'), 0, 0);
            $this->CheckBox(114, $y_start + 6, 3);
            $this->SetXY(117, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'ไม่มีหลักฐาน'), 0, 0);

            // เส้นขวา
            $this->Line(140, $y_start + 12, 140, $y_start + -7);

            // เส้นแนวนอน
            $this->Line(200, $y_start + 12, 10, $y_start + 12);

            // ประกันอื่นๆ
            $this->SetXY(141, $y_start + -7);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'ประกันอื่นๆ (ถ้ามี)'), 0, 0);
            $this->CheckBox(142, $y_start + -1, 3);
            $this->SetXY(146, $y_start + -2);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'ประกันชีวิต'), 0, 0);
            $this->CheckBox(142, $y_start + 3, 3);
            $this->SetXY(146, $y_start + 2);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'ไม่มีหลักฐาน'), 0, 0);
            $this->SetXY(141, $y_start + 6);
            $this->Cell(20, 5, iconv('UTF-8', 'cp874', 'เลขทะเบียนรถ:'), 0, 0);
            $this->UnderLine(158, $y_start + 7, 40);

            $y_start += 7;
            $this->SetXY(100, $y_start + 5);
            $this->SetFont('THSarabun', 'B', 11);
            $this->Cell(20, 5, iconv('UTF-8', 'cp874', 'สภาพผู้ป่วย'), 0, 0);

            // เส้นแนวนอน
            $this->Line(200, $y_start + 10, 10, $y_start + 10);

            $y_start += 6;
            $this->SetXY(15, $y_start + 5);
            $this->Cell(20, 5, iconv('UTF-8', 'cp874', 'สิทธิการรักษา:'), 0, 0);
            $this->CheckBox(32, $y_start + 6, 3);
            $this->SetXY(35, $y_start + 5);
            $this->SetFont('THSarabun', '', 11);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'บาดเจ็บ/อุบัติเหตุ'), 0, 0);
            $this->CheckBox(55, $y_start + 6, 3);
            $this->SetXY(58, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'ป่วยฉุกเฉิน'), 0, 0);

            // Vital Signs Table
            $y_table = $y_start + 10;
            $this->SetXY(10, $y_table);
            $this->SetFont('THSarabun', 'B', 11);

            // ความกว้างรวม = 190 mm
            $vital_widths = [
                'Time' => 20,
                'T' => 20,
                'BP' => 20,
                'PR' => 20,
                'RR' => 20,
                'E' => 20,
                'V' => 20,
                'M' => 20,
                'DTX' => 30
            ];
            // ปรับ Vital Signs กับ Neuro Signs
            $width_vital = $vital_widths['T'] + $vital_widths['BP'] + $vital_widths['PR'] + $vital_widths['RR']; // 65
            $width_neuro = $vital_widths['E'] + $vital_widths['V'] + $vital_widths['M']; // 45

            $x = 10;
            $this->Cell($vital_widths['Time'], 8, 'Time', 1, 0, 'C');
            $this->Cell($width_vital, 4, 'Vital Signs', 1, 0, 'C');
            $this->Cell($width_neuro, 4, 'Neuro Signs', 1, 0, 'C');
            $this->Cell($vital_widths['DTX'], 8, 'DTX', 1, 0, 'C');

            // แถวที่สองของหัวตาราง
            $this->SetXY($x + $vital_widths['Time'], $y_table + 4);
            foreach (['T', 'BP', 'PR', 'RR'] as $key) {
                $this->Cell($vital_widths[$key], 4, $key, 1, 0, 'C');
            }
            foreach (['E', 'V', 'M'] as $key) {
                $this->Cell($vital_widths[$key], 4, $key, 1, 0, 'C');
            }

            // แถวข้อมูล (1 แถว)
            $this->SetXY($x, $y_table + 8);
            $this->SetFont('THSarabun', '', 9);
            foreach (['Time', 'T', 'BP', 'PR', 'RR', 'E', 'V', 'M', 'DTX'] as $key) {
                $this->Cell($vital_widths[$key], 10, '', 1, 0, 'C');
            }

            $y_start += 24;
            $this->SetXY(15, $y_start + 5);
            $this->SetFont('THSarabun', 'B', 11);
            $this->Cell(20, 5, iconv('UTF-8', 'cp874', 'ความรู้สึกตัว'), 0, 0);
            $this->CheckBox(32, $y_start + 6, 3);
            $this->SetXY(35, $y_start + 5);
            $this->SetFont('THSarabun', '', 11);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'รู้สึกตัวดี'), 0, 0);
            $this->CheckBox(48, $y_start + 6, 3);
            $this->SetXY(51, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'ซึม'), 0, 0);
            $this->CheckBox(65, $y_start + 6, 3);
            $this->SetXY(68, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'หมดสติปลุกตื่น'), 0, 0);
            $this->CheckBox(86, $y_start + 6, 3);
            $this->SetXY(89, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'หมดสติปลุกไม่ตื่น'), 0, 0);
            $this->CheckBox(109, $y_start + 6, 3);
            $this->SetXY(112, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'เอะอะโวยวาย'), 0, 0);

            $y_start += 5;
            $this->SetXY(15, $y_start + 5);
            $this->SetFont('THSarabun', 'B', 11);
            $this->Cell(20, 5, iconv('UTF-8', 'cp874', 'การหายใจ'), 0, 0);
            $this->CheckBox(32, $y_start + 6, 3);
            $this->SetXY(35, $y_start + 5);
            $this->SetFont('THSarabun', '', 11);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'ปกติ'), 0, 0);
            $this->CheckBox(48, $y_start + 6, 3);
            $this->SetXY(51, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'เร็ว'), 0, 0);
            $this->CheckBox(65, $y_start + 6, 3);
            $this->SetXY(68, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'ช้า'), 0, 0);
            $this->CheckBox(86, $y_start + 6, 3);
            $this->SetXY(89, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'ไม่สม่ำเสมอ'), 0, 0);
            $this->CheckBox(109, $y_start + 6, 3);
            $this->SetXY(112, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'ไม่หายใจ'), 0, 0);

            $y_start += 5;
            $this->SetXY(15, $y_start + 5);
            $this->SetFont('THSarabun', 'B', 11);
            $this->Cell(20, 5, iconv('UTF-8', 'cp874', 'บาดแผล'), 0, 0);
            $this->CheckBox(32, $y_start + 6, 3);
            $this->SetXY(35, $y_start + 5);
            $this->SetFont('THSarabun', '', 11);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'ไม่มี'), 0, 0);
            $this->CheckBox(48, $y_start + 6, 3);
            $this->SetXY(51, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'แผลถลอก'), 0, 0);
            $this->CheckBox(65, $y_start + 6, 3);
            $this->SetXY(68, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'ฉีกขาด/ตัด'), 0, 0);
            $this->CheckBox(86, $y_start + 6, 3);
            $this->SetXY(89, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'แผลฟกช้ำ'), 0, 0);
            $this->CheckBox(109, $y_start + 6, 3);
            $this->SetXY(112, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'แผลไหม้'), 0, 0);
            $this->CheckBox(123, $y_start + 6, 3);
            $this->SetXY(126, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'ถูกยิง'), 0, 0);
            $this->CheckBox(134, $y_start + 6, 3);
            $this->SetXY(137, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'ถูกแทง'), 0, 0);
            $this->CheckBox(147, $y_start + 6, 3);
            $this->SetXY(150, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'อวัยวะตัดขาด'), 0, 0);
            $this->CheckBox(167, $y_start + 6, 3);
            $this->SetXY(170, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'ถูกระเบิด'), 0, 0);

            $y_start += 5;
            $this->SetXY(15, $y_start + 5);
            $this->SetFont('THSarabun', 'B', 11);
            $this->Cell(20, 5, iconv('UTF-8', 'cp874', 'กระดูกผิดรูป'), 0, 0);
            $this->CheckBox(32, $y_start + 6, 3);
            $this->SetXY(35, $y_start + 5);
            $this->SetFont('THSarabun', '', 11);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'ไม่มี'), 0, 0);
            $this->CheckBox(48, $y_start + 6, 3);
            $this->SetXY(51, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'ผิดรูป'), 0, 0);

            // เส้นแนวนอน
            $this->Line(200, $y_start + 11, 10, $y_start + 11);

            $y_start += 6;
            $this->SetXY(15, $y_start + 5);
            $this->SetFont('THSarabun', 'B', 11);
            $this->Cell(20, 5, iconv('UTF-8', 'cp874', 'อวัยวะ'), 0, 0);
            $this->CheckBox(32, $y_start + 6, 3);
            $this->SetXY(35, $y_start + 5);
            $this->SetFont('THSarabun', '', 11);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'บาดเจ็บ/อุบัติเหตุ'), 0, 0);
            $this->CheckBox(55, $y_start + 6, 3);
            $this->SetXY(58, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'ป่วยฉุกเฉิน'), 0, 0);

            // เส้นแนวนอน
            $this->Line(200, $y_start + 10, 10, $y_start + 10);

            $y_start += 5;
            $this->SetXY(15, $y_start + 5);
            $this->SetFont('THSarabun', 'B', 11);
            $this->Cell(20, 5, iconv('UTF-8', 'cp874', 'การช่วยเหลือ'), 0, 0);

            // เส้นแนวนอน
            $this->Line(200, $y_start + 10, 10, $y_start + 10);

            $y_start += 5;
            $this->SetXY(15, $y_start + 5);
            $this->SetFont('THSarabun', 'B', 11);
            $this->Cell(20, 5, iconv('UTF-8', 'cp874', 'ทางเดินหายใจ/การหายใจ'), 0, 0);
            $this->SetFont('THSarabun', '', 11);
            $this->CheckBox(45, $y_start + 6, 3);
            $this->SetXY(48, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'ไม่'), 0, 0);
            $this->CheckBox(59, $y_start + 6, 3);
            $this->SetXY(62, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'เปิดทางเดินหายใจ'), 0, 0);
            $this->CheckBox(88, $y_start + 6, 3);
            $this->SetXY(91, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'ใส่ Oral airway'), 0, 0);
            $this->CheckBox(110, $y_start + 6, 3);
            $this->SetXY(113, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'ให้ O2 canula/mask'), 0, 0);
            $this->CheckBox(138, $y_start + 6, 3);
            $this->SetXY(141, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'Ambu bag'), 0, 0);
            $this->CheckBox(156, $y_start + 6, 3);
            $this->SetXY(159, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'Pocket Mask'), 0, 0);

            $y_start += 5;
            $this->SetXY(15, $y_start + 5);
            $this->SetFont('THSarabun', 'B', 11);
            $this->Cell(20, 5, iconv('UTF-8', 'cp874', 'บาดแผล/ห้ามเลือด'), 0, 0);
            $this->SetFont('THSarabun', '', 11);
            $this->CheckBox(45, $y_start + 6, 3);
            $this->SetXY(48, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'ไม่'), 0, 0);
            $this->CheckBox(59, $y_start + 6, 3);
            $this->SetXY(62, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'การกดห้ามเลือด'), 0, 0);
            $this->CheckBox(88, $y_start + 6, 3);
            $this->SetXY(91, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'ทำแผล'), 0, 0);

            $y_start += 5;
            $this->SetXY(15, $y_start + 5);
            $this->SetFont('THSarabun', 'B', 11);
            $this->Cell(20, 5, iconv('UTF-8', 'cp874', 'การดามกระดูก'), 0, 0);
            $this->SetFont('THSarabun', '', 11);
            $this->CheckBox(45, $y_start + 6, 3);
            $this->SetXY(48, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'ไม่'), 0, 0);
            $this->CheckBox(59, $y_start + 6, 3);
            $this->SetXY(62, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'เฝือกลม/ไม้ดาม/sling'), 0, 0);
            $this->CheckBox(88, $y_start + 6, 3);
            $this->SetXY(91, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'เฝือกดามคอและกระดานรองหลังยาว'), 0, 0);
            $this->CheckBox(132, $y_start + 6, 3);
            $this->SetXY(135, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'เฝือกหลังและคอ (KED)'), 0, 0);

            $y_start += 5;
            $this->SetXY(15, $y_start + 5);
            $this->SetFont('THSarabun', 'B', 11);
            $this->Cell(20, 5, iconv('UTF-8', 'cp874', 'ช่วยฟื้นคืนชีพ'), 0, 0);
            $this->SetFont('THSarabun', '', 11);
            $this->CheckBox(45, $y_start + 6, 3);
            $this->SetXY(48, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'ไม่ได้ทำ'), 0, 0);
            $this->CheckBox(59, $y_start + 6, 3);
            $this->SetXY(62, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'ทำ'), 0, 0);

            // เส้นแนวนอน
            $this->Line(200, $y_start + 10, 10, $y_start + 10);

            $y_start += 5;
            $this->SetXY(15, $y_start + 5);
            $this->SetFont('THSarabun', 'B', 11);
            $this->Cell(20, 5, iconv('UTF-8', 'cp874', 'ผลการดูแลรักษาขั้นตัน'), 0, 0);
            $this->SetFont('THSarabun', '', 11);
            $this->CheckBox(43, $y_start + 6, 3);
            $this->SetXY(46, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'ไม่ยอมให้รักษา'), 0, 0);
            $this->CheckBox(65, $y_start + 6, 3);
            $this->SetXY(68, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'ทุเลา'), 0, 0);
            $this->CheckBox(77, $y_start + 6, 3);
            $this->SetXY(80, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'คงเดิม/คงที่'), 0, 0);
            $this->CheckBox(96, $y_start + 6, 3);
            $this->SetXY(99, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'ทรุดหนัก'), 0, 0);
            $this->CheckBox(112, $y_start + 6, 3);
            $this->SetXY(115, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'เสียชีวิต ณ จุดเกิดเหตุ'), 0, 0);
            $this->CheckBox(141, $y_start + 6, 3);
            $this->SetXY(144, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'เสียชีวิตขณะนำส่ง'), 0, 0);

            $this->SetY($this->GetY() + 6);
        }

        // Section 4: เกณฑ์การตัดสินใจส่งโรงบาล (โดยหัวหน้าทีมและ/ผ่านการเห็นชอบของศูนย์ฯ)
        function Section4($data)
        {
            $this->SetFont('THSarabun', 'B', 12);
            $this->Cell(0, 6, iconv('UTF-8', 'cp874', '4. เกณฑ์การตัดสินใจส่งโรงบาล (โดยหัวหน้าทีมและ/ผ่านการเห็นชอบของศูนย์ฯ)'), 0, 1);

            $y_start = $this->GetY();
            $this->Rect(10, $y_start, 190, 19);

            $y_start += -4;
            $this->SetXY(15, $y_start + 5);
            $this->SetFont('THSarabun', 'B', 11);
            $this->Cell(20, 5, iconv('UTF-8', 'cp874', 'นำส่งห้องฉุกเฉินโรงพยาบาล'), 0, 0);
            $this->UnderLine(47, $y_start + 6, 75);

            $this->SetFont('THSarabun', '', 11);
            $this->CheckBox(123, $y_start + 6, 3);
            $this->SetXY(126, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'รพ.รัฐ'), 0, 0);
            $this->CheckBox(137, $y_start + 6, 3);
            $this->SetXY(140, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'รพ.เอกชน'), 0, 0);

            $y_start += 5;
            $this->SetXY(15, $y_start + 5);
            $this->SetFont('THSarabun', 'B', 11);
            $this->Cell(20, 5, iconv('UTF-8', 'cp874', 'เหตุผล'), 0, 0);
            $this->SetFont('THSarabun', '', 11);
            $this->CheckBox(26, $y_start + 6, 3);
            $this->SetXY(29, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'เหมาะสม/สามารถรักษาได้'), 0, 0);
            $this->CheckBox(60, $y_start + 6, 3);
            $this->SetXY(63, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'อยู่ใกล้'), 0, 0);
            $this->CheckBox(74, $y_start + 6, 3);
            $this->SetXY(77, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'มีหลักประกัน'), 0, 0);
            $this->CheckBox(94, $y_start + 6, 3);
            $this->SetXY(97, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'เป็นผู้ป่วยเก่า'), 0, 0);
            $this->CheckBox(114, $y_start + 6, 3);
            $this->SetXY(117, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'เป็นความประสงค์ (เลือกได้มากกว่า 1 ข้อ)'), 0, 0);

            $y_start += 5;
            $this->SetXY(15, $y_start + 5);
            $this->SetFont('THSarabun', 'B', 11);
            $this->Cell(20, 5, iconv('UTF-8', 'cp874', 'ผู้สรุปรายงาน'), 0, 0);
            $this->UnderLine(31, $y_start + 6, 75);

            $this->SetY($this->GetY() + 30);
        }

        // Section 5: การประเมิน
        function Section5($data)
        {
            $this->SetFont('THSarabun', 'B', 12);
            $this->Cell(0, 6, iconv('UTF-8', 'cp874', '5. การประเมิน/รับรองการนำส่ง (โดยแพทย์ พยาบาล ประจำโรงพยาบาลที่รับดูแลต่อ)'), 0, 1);

            $y_start = $this->GetY();
            $this->Rect(10, $y_start, 190, 32);

            $y_start += -4;
            $this->SetXY(15, $y_start + 5);
            $this->SetFont('THSarabun', 'B', 11);
            $this->Cell(20, 5, iconv('UTF-8', 'cp874', 'HN'), 0, 0);
            $this->UnderLine(20, $y_start + 6, 60);
            $this->SetXY(80, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'การวินิฉัยโรค'), 0, 0);
            $this->UnderLine(96, $y_start + 6, 100);

            $y_start += 5;
            $this->SetXY(15, $y_start + 5);
            $this->SetFont('THSarabun', '', 11);
            $this->Cell(20, 5, iconv('UTF-8', 'cp874', 'ระดับความรุนแรง'), 0, 0);
            $this->CheckBox(37, $y_start + 6, 3);
            $this->SetXY(40, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'แดง (วิกฤติ)'), 0, 0);
            $this->CheckBox(56, $y_start + 6, 3);
            $this->SetXY(59, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'เหลือง (เร่งด่วน)'), 0, 0);
            $this->CheckBox(79, $y_start + 6, 3);
            $this->SetXY(82, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'เขียว (ไม่รุนแรง)'), 0, 0);
            $this->CheckBox(102, $y_start + 6, 3);
            $this->SetXY(105, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'ขาว (ทั่วไป)'), 0, 0);
            $this->CheckBox(121, $y_start + 6, 3);
            $this->SetXY(124, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'ดำ (รับบริการสาธารณสุขอื่น)'), 0, 0);

            // เส้นแนวนอน
            $this->Line(200, $y_start + 10, 10, $y_start + 10);

            $y_start += 5;
            $this->SetXY(15, $y_start + 5);
            $this->SetFont('THSarabun', 'B', 11);
            $this->Cell(20, 5, iconv('UTF-8', 'cp874', 'ทางเดินหายใจ'), 0, 0);
            $this->SetFont('THSarabun', '', 11);
            $this->CheckBox(35, $y_start + 6, 3);
            $this->SetXY(38, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'ไม่จำเป็น'), 0, 0);
            $this->CheckBox(55, $y_start + 6, 3);
            $this->SetXY(58, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'ไม่ได้ทำ'), 0, 0);
            $this->CheckBox(74, $y_start + 6, 3);
            $this->SetXY(77, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'ทำและเหมาะสม'), 0, 0);
            $this->CheckBox(110, $y_start + 6, 3);
            $this->SetXY(113, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'ทำแต่ไม่เหมาะสม ระบุ'), 0, 0);
            $this->UnderLine(138, $y_start + 6, 58);

            $y_start += 5;
            $this->SetXY(15, $y_start + 5);
            $this->SetFont('THSarabun', 'B', 11);
            $this->Cell(20, 5, iconv('UTF-8', 'cp874', 'การห้ามเลือด'), 0, 0);
            $this->SetFont('THSarabun', '', 11);
            $this->CheckBox(35, $y_start + 6, 3);
            $this->SetXY(38, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'ไม่จำเป็น'), 0, 0);
            $this->CheckBox(55, $y_start + 6, 3);
            $this->SetXY(58, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'ไม่ได้ทำ'), 0, 0);
            $this->CheckBox(74, $y_start + 6, 3);
            $this->SetXY(77, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'ทำและเหมาะสม'), 0, 0);
            $this->CheckBox(110, $y_start + 6, 3);
            $this->SetXY(113, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'ทำแต่ไม่เหมาะสม ระบุ'), 0, 0);
            $this->UnderLine(138, $y_start + 6, 58);

            $y_start += 5;
            $this->SetXY(15, $y_start + 5);
            $this->SetFont('THSarabun', 'B', 11);
            $this->Cell(20, 5, iconv('UTF-8', 'cp874', 'การดามกระดูก'), 0, 0);
            $this->SetFont('THSarabun', '', 11);
            $this->CheckBox(35, $y_start + 6, 3);
            $this->SetXY(38, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'ไม่จำเป็น'), 0, 0);
            $this->CheckBox(55, $y_start + 6, 3);
            $this->SetXY(58, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'ไม่ได้ทำ'), 0, 0);
            $this->CheckBox(74, $y_start + 6, 3);
            $this->SetXY(77, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'ทำและเหมาะสม'), 0, 0);
            $this->CheckBox(110, $y_start + 6, 3);
            $this->SetXY(113, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'ทำแต่ไม่เหมาะสม ระบุ'), 0, 0);
            $this->UnderLine(138, $y_start + 6, 58);

            // เส้นแนวนอน
            $this->Line(200, $y_start + 10, 10, $y_start + 10);

            $y_start += 5;
            $this->SetXY(15, $y_start + 5);
            $this->SetFont('THSarabun', 'B', 11);
            $this->Cell(20, 5, iconv('UTF-8', 'cp874', 'ชื่อผู้ประเมิน'), 0, 0);
            $this->UnderLine(30, $y_start + 6, 55);
            $this->SetFont('THSarabun', '', 11);
            $this->SetXY(85, $y_start + 5);
            $this->Cell(50, 5, iconv('UTF-8', 'cp874', 'ตำแหน่ง'), 0, 0);
            $this->CheckBox(97, $y_start + 6, 3);
            $this->SetXY(100, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'แพทย์'), 0, 0);
            $this->CheckBox(110, $y_start + 6, 3);
            $this->SetXY(113, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'พยาบาล'), 0, 0);
            $this->CheckBox(125, $y_start + 6, 3);
            $this->SetXY(128, $y_start + 5);
            $this->Cell(20, 5, iconv('UTF-8', 'cp874', 'อื่น ๆ'), 0, 0);
            $this->UnderLine(135, $y_start + 6, 61);

            $this->SetY($this->GetY() + 6);
        }

        // Section 6: ผลการรักษาที่/ในโรงพยาบาล (ติดตามใน 24 ชั่วโมง)
        function Section6($data)
        {
            $this->SetFont('THSarabun', 'B', 12);
            $this->Cell(0, 6, iconv('UTF-8', 'cp874', '6. ผลการรักษาที่/ในโรงพยาบาล (ติดตามใน 24 ชั่วโมง)'), 0, 1);

            $y_start = $this->GetY();
            $this->Rect(10, $y_start, 190, 12);

            $y_start += -4;
            $this->SetXY(15, $y_start + 5);
            $this->SetFont('THSarabun', 'B', 11);
            $this->Cell(20, 5, iconv('UTF-8', 'cp874', 'Adlmitted'), 0, 0);
            $this->SetFont('THSarabun', '', 11);
            $this->CheckBox(35, $y_start + 6, 3);
            $this->SetXY(38, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'Yes'), 0, 0);
            $this->CheckBox(46, $y_start + 6, 3);
            $this->SetXY(49, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'No'), 0, 0);

            $y_start += 5;
            $this->CheckBox(35, $y_start + 6, 3);
            $this->SetXY(38, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'ทุเลา'), 0, 0);
            $this->CheckBox(46, $y_start + 6, 3);
            $this->SetXY(49, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'รักษาที่อื่น'), 0, 0);
            $this->CheckBox(63, $y_start + 6, 3);
            $this->SetXY(66, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'ยังรักษาใน รพ.'), 0, 0);
            $this->CheckBox(84, $y_start + 6, 3);
            $this->SetXY(87, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'เสียชีวิตใน รพ.'), 0, 0);
            $this->CheckBox(105, $y_start + 6, 3);
            $this->SetXY(108, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'ปฏิเสธการรักษา/หนีกลับ'), 0, 0);
            $this->CheckBox(137, $y_start + 6, 3);
            $this->SetXY(140, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'กลับไปตายบ้าน'), 0, 0);
            $this->CheckBox(159, $y_start + 6, 3);
            $this->SetXY(162, $y_start + 5);
            $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'กลับไปตายบ้าน'), 0, 0);

            $this->SetY($this->GetY() + 25);
        }
    }
    try {
        $pdf = new MedicalForm();
        $pdf->AddPage();
        $data = $pdf->LoadData($pdo, $id);

        if ($data) {
            $pdf->Section1($data);
            $pdf->Section2($data);
            $pdf->Section3($data);
            $pdf->Section4($data);
            $pdf->Section5($data);
            $pdf->Section6($data);
            $pdf->Output();
        } else {
            echo "ไม่พบข้อมูล ID นี้";
        }
        exit;
    } catch (PDOException $e) {
        echo "เกิดข้อผิดพลาด: " . $e->getMessage();
    }
} else {

    include_once("../sidebar.php");

    // ฟังก์ชันสำหรับบันทึกข้อมูล
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {
        try {
            // เก็บข้อมูลจากฟอร์ม
            $data = [
                'unit_name' => $_POST['unit_name'] ?? '',
                'record_date' => $_POST['prg_date'] ?? date('Y-m-d'),
                'operation_number' => $_POST['operation_number'] ?? '',
                'staff_members' => implode(', ', $_POST['staff_members'] ?? []),
                'operation_result' => $_POST['operation_result'] ?? '',
                'incident_location' => $_POST['incident_location'] ?? '',
                'incident_description' => $_POST['incident_description'] ?? '',
                'receive_time' => $_POST['receive_time'] ?? null,
                'order_time' => $_POST['order_time'] ?? null,
                'exit_base_time' => $_POST['exit_base_time'] ?? null,
                'arrive_scene_time' => $_POST['arrive_scene_time'] ?? null,
                'leave_scene_time' => $_POST['leave_scene_time'] ?? null,
                'arrive_hospital_time' => $_POST['arrive_hospital_time'] ?? null,
                'return_base_time' => $_POST['return_base_time'] ?? null,
                'response_time' => $_POST['response_time'] ?? 0,
                'total_distance_go' => $_POST['total_distance_go'] ?? 0,
                'total_distance_back' => $_POST['total_distance_back'] ?? 0,
                'number_km_1' => $_POST['number_km_1'] ?? 0,
                'number_km_2' => $_POST['number_km_2'] ?? 0,
                'number_km_3' => $_POST['number_km_3'] ?? 0,
                'number_km_4' => $_POST['number_km_4'] ?? 0,
                'total_km_go' => $_POST['total_km_go'] ?? 0,
                'total_km_go_home' => $_POST['total_km_go_home'] ?? 0,
                'total_km_go_hosp' => $_POST['total_km_go_hosp'] ?? 0,
                'patient_name' => $_POST['patient_name'] ?? '',
                'patient_age' => $_POST['patient_age'] ?? 0,
                'patient_gender' => $_POST['patient_gender'] ?? '',
                'patient_id_card' => $_POST['patient_id_card'] ?? '',
                'treatment_rights' => $_POST['treatment_rights'] ?? '',
                'other_insurance' => $_POST['other_insurance'] ?? '',
                'license_plate' => $_POST['license_plate'] ?? '',
                'patient_condition' => $_POST['patient_condition'] ?? '',
                'vital_time' => $_POST['vital_time'] ?? '',
                'vital_t' => $_POST['vital_t'] ?? '',
                'vital_bp' => $_POST['vital_bp'] ?? '',
                'vital_pr' => $_POST['vital_pr'] ?? '',
                'vital_rr' => $_POST['vital_rr'] ?? '',
                'neuro_e' => $_POST['neuro_e'] ?? '',
                'neuro_v' => $_POST['neuro_v'] ?? '',
                'neuro_m' => $_POST['neuro_m'] ?? '',
                'dtx' => $_POST['dtx'] ?? '',
                'consciousness' => $_POST['consciousness'] ?? '',
                'breathing_status' => $_POST['breathing_status'] ?? '',
                'wound_type' => $_POST['wound_type'] ?? '',
                'bone_status' => $_POST['bone_status'] ?? '',
                'affected_organ' => $_POST['affected_organ'] ?? '',
                'airway_assistance' => $_POST['airway_assistance'] ?? '',
                'wound_care' => $_POST['wound_care'] ?? '',
                'bone_immobilization' => $_POST['bone_immobilization'] ?? '',
                'resuscitation' => $_POST['resuscitation'] ?? '',
                'treatment_result' => $_POST['treatment_result'] ?? '',
                'hospital_destination' => $_POST['hospital_destination'] ?? '',
                'hospital_type' => $_POST['hospital_type'] ?? '',
                'reasons' => isset($_POST['reasons']) ? implode(', ', $_POST['reasons']) : '',
                'report_summarizer' => $_POST['report_summarizer'] ?? '',
            ];

            // สร้างคำสั่ง SQL
            $sql = "INSERT INTO incident_areas (
            unit_name, record_date, operation_number, staff_members, operation_result, 
            incident_location, incident_description, receive_time, order_time, 
            exit_base_time, arrive_scene_time, leave_scene_time, arrive_hospital_time, 
            return_base_time, response_time, total_distance_go, total_distance_back, 
            number_km_1, number_km_2, number_km_3, number_km_4, total_km_go, total_km_go_home, total_km_go_hosp, 
            patient_name, patient_age, patient_gender, patient_id_card, treatment_rights, 
            other_insurance, license_plate, patient_condition, vital_time, vital_t, vital_bp, vital_pr, vital_rr, neuro_e, neuro_v, neuro_m, dtx, consciousness, 
            breathing_status, wound_type, bone_status, affected_organ, airway_assistance, 
            wound_care, bone_immobilization, resuscitation, treatment_result, 
            hospital_destination, hospital_type, reasons, report_summarizer
        ) VALUES (
            :unit_name, :record_date, :operation_number, :staff_members, :operation_result, 
            :incident_location, :incident_description, :receive_time, :order_time, 
            :exit_base_time, :arrive_scene_time, :leave_scene_time, :arrive_hospital_time, 
            :return_base_time, :response_time, :total_distance_go, :total_distance_back, 
            :number_km_1, :number_km_2, :number_km_3, :number_km_4, :total_km_go, :total_km_go_home, :total_km_go_hosp, 
            :patient_name, :patient_age, :patient_gender, :patient_id_card, :treatment_rights, 
            :other_insurance, :license_plate, :patient_condition, :vital_time, :vital_t, :vital_bp, :vital_pr, :vital_rr, :neuro_e, :neuro_v, :neuro_m, :dtx, :consciousness, 
            :breathing_status, :wound_type, :bone_status, :affected_organ, :airway_assistance, 
            :wound_care, :bone_immobilization, :resuscitation, :treatment_result, 
            :hospital_destination, :hospital_type, :reasons, :report_summarizer
        )";

            $stmt = $pdo->prepare($sql);
            $stmt->execute($data);

            $_SESSION['success'] = "บันทึกข้อมูลเรียบร้อยแล้ว";
            header("Location: ../event/");
            exit();
        } catch (PDOException $e) {
            $_SESSION['error'] = "เกิดข้อผิดพลาดในการบันทึกข้อมูล: " . $e->getMessage();
            header("Location: index.php?tambah");
            exit();
        }
    }

    // ฟังก์ชันสำหรับดึงข้อมูลเดียว
    function getIncidentById($pdo, $id)
    {
        $stmt = $pdo->prepare("SELECT * FROM incident_areas WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ฟังก์ชันสำหรับอัปเดตข้อมูล
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
        try {
            $id = $_POST['id'];
            $data = [
                'id' => $id,
                'unit_name' => $_POST['unit_name'] ?? '',
                'record_date' => $_POST['prg_date'] ?? date('Y-m-d'),
                'operation_number' => $_POST['operation_number'] ?? '',
                'staff_members' => implode(', ', $_POST['staff_members'] ?? []),
                'operation_result' => $_POST['operation_result'] ?? '',
                'incident_location' => $_POST['incident_location'] ?? '',
                'incident_description' => $_POST['incident_description'] ?? '',
                'receive_time' => $_POST['receive_time'] ?? null,
                'order_time' => $_POST['order_time'] ?? null,
                'exit_base_time' => $_POST['exit_base_time'] ?? null,
                'arrive_scene_time' => $_POST['arrive_scene_time'] ?? null,
                'leave_scene_time' => $_POST['leave_scene_time'] ?? null,
                'arrive_hospital_time' => $_POST['arrive_hospital_time'] ?? null,
                'return_base_time' => $_POST['return_base_time'] ?? null,
                'response_time' => $_POST['response_time'] ?? 0,
                'total_distance_go' => $_POST['total_distance_go'] ?? 0,
                'total_distance_back' => $_POST['total_distance_back'] ?? 0,
                'number_km_1' => $_POST['number_km_1'] ?? 0,
                'number_km_2' => $_POST['number_km_2'] ?? 0,
                'number_km_3' => $_POST['number_km_3'] ?? 0,
                'number_km_4' => $_POST['number_km_4'] ?? 0,
                'total_km_go' => $_POST['total_km_go'] ?? 0,
                'total_km_go_home' => $_POST['total_km_go_home'] ?? 0,
                'total_km_go_hosp' => $_POST['total_km_go_hosp'] ?? 0,
                'patient_name' => $_POST['patient_name'] ?? '',
                'patient_age' => $_POST['patient_age'] ?? 0,
                'patient_gender' => $_POST['patient_gender'] ?? '',
                'patient_id_card' => $_POST['patient_id_card'] ?? '',
                'treatment_rights' => $_POST['treatment_rights'] ?? '',
                'other_insurance' => $_POST['other_insurance'] ?? '',
                'license_plate' => $_POST['license_plate'] ?? '',
                'patient_condition' => $_POST['patient_condition'] ?? '',
                'vital_time' => $_POST['vital_time'] ?? '',
                'vital_t' => $_POST['vital_t'] ?? '',
                'vital_bp' => $_POST['vital_bp'] ?? '',
                'vital_pr' => $_POST['vital_pr'] ?? '',
                'vital_rr' => $_POST['vital_rr'] ?? '',
                'neuro_e' => $_POST['neuro_e'] ?? '',
                'neuro_v' => $_POST['neuro_v'] ?? '',
                'neuro_m' => $_POST['neuro_m'] ?? '',
                'dtx' => $_POST['dtx'] ?? '',
                'consciousness' => $_POST['consciousness'] ?? '',
                'breathing_status' => $_POST['breathing_status'] ?? '',
                'wound_type' => $_POST['wound_type'] ?? '',
                'bone_status' => $_POST['bone_status'] ?? '',
                'affected_organ' => $_POST['affected_organ'] ?? '',
                'airway_assistance' => $_POST['airway_assistance'] ?? '',
                'wound_care' => $_POST['wound_care'] ?? '',
                'bone_immobilization' => $_POST['bone_immobilization'] ?? '',
                'resuscitation' => $_POST['resuscitation'] ?? '',
                'treatment_result' => $_POST['treatment_result'] ?? '',
                'hospital_destination' => $_POST['hospital_destination'] ?? '',
                'hospital_type' => $_POST['hospital_type'] ?? '',
                'reasons' => isset($_POST['reasons']) ? implode(', ', $_POST['reasons']) : '',
                'report_summarizer' => $_POST['report_summarizer'] ?? '',
            ];

            $sql = "UPDATE incident_areas SET 
            unit_name = :unit_name,
            record_date = :record_date,
            operation_number = :operation_number,
            staff_members = :staff_members,
            operation_result = :operation_result,
            incident_location = :incident_location,
            incident_description = :incident_description,
            receive_time = :receive_time,
            order_time = :order_time,
            exit_base_time = :exit_base_time,
            arrive_scene_time = :arrive_scene_time,
            leave_scene_time = :leave_scene_time,
            arrive_hospital_time = :arrive_hospital_time,
            return_base_time = :return_base_time,
            response_time = :response_time,
            total_distance_go = :total_distance_go,
            total_distance_back = :total_distance_back,
            number_km_1 = :number_km_1,
            number_km_2 = :number_km_2,
            number_km_3 = :number_km_3,
            number_km_4 = :number_km_4,
            total_km_go = :total_km_go,
            total_km_go_home = :total_km_go_home,
            total_km_go_hosp = :total_km_go_hosp,
            patient_name = :patient_name,
            patient_age = :patient_age,
            patient_gender = :patient_gender,
            patient_id_card = :patient_id_card,
            treatment_rights = :treatment_rights,
            other_insurance = :other_insurance,
            license_plate = :license_plate,
            patient_condition = :patient_condition,
            vital_time = :vital_time,
            vital_t = :vital_t,
            vital_bp = :vital_bp,
            vital_pr = :vital_pr,
            vital_rr = :vital_rr,
            neuro_e = :neuro_e,
            neuro_v = :neuro_v,
            neuro_m = :neuro_m,
            dtx = :dtx,
            consciousness = :consciousness,
            breathing_status = :breathing_status,
            wound_type = :wound_type,
            bone_status = :bone_status,
            affected_organ = :affected_organ,
            airway_assistance = :airway_assistance,
            wound_care = :wound_care,
            bone_immobilization = :bone_immobilization,
            resuscitation = :resuscitation,
            treatment_result = :treatment_result,
            hospital_destination = :hospital_destination,
            hospital_type = :hospital_type,
            reasons = :reasons,
            report_summarizer = :report_summarizer
            WHERE id = :id";

            $stmt = $pdo->prepare($sql);
            $stmt->execute($data);

            $_SESSION['success'] = "อัปเดตข้อมูลเรียบร้อยแล้ว";
            header("Location: index.php");
            exit();
        } catch (PDOException $e) {
            $_SESSION['error'] = "เกิดข้อผิดพลาดในการอัปเดตข้อมูล: " . $e->getMessage();
            header("Location: index.php?edit=" . $_POST['id']);
            exit();
        }
    }

    // ฟังก์ชันสำหรับลบข้อมูล
    if (isset($_GET['delete'])) {
        try {
            $id = $_GET['delete'];
            $stmt = $pdo->prepare("DELETE FROM incident_areas WHERE id = ?");
            $stmt->execute([$id]);

            $_SESSION['success'] = "ลบข้อมูลเรียบร้อยแล้ว";
            header("Location: ../event/");
            exit();
        } catch (PDOException $e) {
            $_SESSION['error'] = "เกิดข้อผิดพลาดในการลบข้อมูล: " . $e->getMessage();
            header("Location: index.php");
            exit();
        }
    }

    // ฟังก์ชันสำหรับดึงข้อมูลทั้งหมด
    function getAllIncidents($pdo, $filters = [])
    {
        $sql = "SELECT * FROM incident_areas WHERE 1=1";
        $params = [];

        // กรองตามวันที่
        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $sql .= " AND record_date BETWEEN :start_date AND :end_date";
            $params['start_date'] = $filters['start_date'];
            $params['end_date'] = $filters['end_date'];
        }

        // กรองตามผลการปฏิบัติงาน
        if (!empty($filters['operation_result'])) {
            $sql .= " AND operation_result = :operation_result";
            $params['operation_result'] = $filters['operation_result'];
        }

        // กรองตามคำค้นหา
        if (!empty($filters['search'])) {
            $sql .= " AND (patient_name LIKE :search OR report_summarizer LIKE :search)";
            $params['search'] = '%' . $filters['search'] . '%';
        }


        $sql .= " ORDER BY record_date DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ดึงข้อมูลสำหรับแสดงในตาราง
    $filters = [
        'search' => $_GET['search'] ?? '',
        'operation_result' => $_GET['operation_result'] ?? '',
        'start_date' => $_GET['start_date'] ?? '',
        'end_date' => $_GET['end_date'] ?? ''
    ];

    $incidents = getAllIncidents($pdo, $filters);

    // นับสถิติ
    $totalIncidents = $pdo->query("SELECT COUNT(*) FROM incident_areas")->fetchColumn();
    $inAreaIncidents = $pdo->query("SELECT COUNT(*) FROM incident_areas WHERE operation_result = 'พบเหตุ'")->fetchColumn();
    $outAreaIncidents = $pdo->query("SELECT COUNT(*) FROM incident_areas WHERE operation_result = 'ไม่พบเหตุ'")->fetchColumn();


?>
    <!-- ส่วน HTML ที่มีอยู่เดิม -->
    <div class="page-inner">
        <?php if (!isset($_GET['tambah']) && !isset($_GET['edit']) && !isset($_GET['details'])) { ?>

            <!-- Success/Error Messages -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['success'];
                    unset($_SESSION['success']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="page-header">
                <h3 class="fw-bold mb-3">พื้นที่ออกเหตุและเวลา</h3>
                <div class="ms-md-auto py-2 py-md-0">
                    <div class="btn-group">
                        <a href="?tambah" class="btn btn-primary btn-round">เพิ่มข้อมูล</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card p-3">
                        <h5 class="mb-3 text-primary">
                            <i class="fas fa-filter"></i><b> ตัวกรองพื้นที่ออกเหตุและเวลา</b>
                        </h5>
                        <form method="get" action="index.php">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">ค้นหา</label>
                                        <input type="text" class="form-control" name="search" placeholder="พิมพ์ชื่อผู้ป่วย/ชื่อผู้บันทึก" value="<?= htmlspecialchars($filters['search'] ?? '') ?>" />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="name_en" class="form-label">ผลการปฏิบัติงาน</label>
                                        <select class="form-control" name="operation_result">
                                            <option value="">เลือกผลการปฏิบัติงาน</option>
                                            <option value="พบเหตุ" <?= ($filters['operation_result'] ?? '') === 'พบเหตุ' ? 'selected' : '' ?>>พบเหตุ</option>
                                            <option value="ไม่พบเหตุ" <?= ($filters['operation_result'] ?? '') === 'ไม่พบเหตุ' ? 'selected' : '' ?>>ไม่พบเหตุ</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="name_en" class="form-label">ตั้งแต่วันที่</label>
                                        <input type="date" class="form-control" name="start_date" value="<?= htmlspecialchars($filters['start_date'] ?? '') ?>" />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="name_en" class="form-label">ถึงวันที่</label>
                                        <input type="date" class="form-control" name="end_date" value="<?= htmlspecialchars($filters['end_date'] ?? '') ?>" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 d-flex justify-content-end align-items-end">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search"></i> ค้นหา
                                        </button>
                                        <?php if (!empty($filters['search']) || !empty($filters['operation_result']) || !empty($filters['start_date']) || !empty($filters['end_date'])): ?>
                                            <a href="../event/" class="btn btn-secondary ms-2">
                                                <i class="fas fa-times"></i> ล้างการค้นหา
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 col-md-4">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-secondary bubble-shadow-small">
                                        <i class="fas fa-ambulance"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">ออกเหตุทั้งหมด (ครั้ง)</p>
                                        <h4 class="card-title"><?= number_format($totalIncidents) ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-info bubble-shadow-small">
                                        <i class="fas fa-map-marked-alt"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">พบเหตุทั้งหมด (ครั้ง)</p>
                                        <h4 class="card-title"><?= number_format($inAreaIncidents) ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-success bubble-shadow-small">
                                        <i class="fas fa-map"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">ไม่พบเหตุทั้งหมด (ครั้ง)</p>
                                        <h4 class="card-title"><?= number_format($outAreaIncidents) ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">พื้นที่ออกเหตุและเวลา</h4>
                        </div>
                        <div class="card-body">
                            <!-- ในส่วนของตารางแสดงข้อมูล -->
                            <div class="table-responsive">
                                <table id="basic-datatables" class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>ประทับเวลา (วันที่)</th>
                                            <th>ผลการปฏิบัติงาน</th>
                                            <th>ชื่อผู้ป่วย</th>
                                            <th>ผู้สรุปรายงาน</th>
                                            <th>เครื่องมือ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($incidents as $incident): ?>
                                            <tr>
                                                <td><?= date('d M Y', strtotime($incident['record_date'])) ?></td>
                                                <td><?= $incident['operation_result'] ?></td>
                                                <td><?= $incident['patient_name'] ?></td>
                                                <td><?= $incident['report_summarizer'] ?></td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="index.php?cetak&id=<?= $incident['id'] ?>" class="btn btn-sm btn-success me-1" title="พิมพ์">
                                                            <i class="fas fa-print"></i>
                                                        </a>
                                                        <a href="?details=<?= $incident['id'] ?>" class="btn btn-sm btn-info me-1" title="ดูรายละเอียด">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="?edit=<?= $incident['id'] ?>" class="btn btn-sm btn-warning me-1" title="แก้ไข">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a href="?delete=<?= $incident['id'] ?>" class="btn btn-sm btn-danger me-1" title="ลบ" onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบข้อมูลนี้?')">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php }
                if (isset($_GET['tambah']) && !isset($_POST['edit'])) { ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <?php if (isset($_SESSION['error'])): ?>
                                        <div class="alert alert-danger col-8 mx-auto text-center p-2 border rounded text-center">
                                            <?= $_SESSION['error'];
                                            unset($_SESSION['error']); ?>
                                        </div>
                                    <?php endif; ?>

                                    <form role="form" method="post" action="./index.php" enctype="multipart/form-data">
                                        <div class="card-header">
                                            <div class="card-title">แบบฟอร์มบันทึกพื้นที่ออกเหตุและเวลา</div>
                                        </div>

                                        <!-- ส่วนที่ 1: ข้อมูลหน่วยงาน -->
                                        <div class="card-body">
                                            <div class="card-title">
                                                <h5>1. หน่วยงาน</h5>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="unit_name">ชื่อหน่วยบริการ</label>
                                                        <input type="text" class="form-control" id="unit_name" name="unit_name" required>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="prg_date">วันที่บันทึก</label>
                                                        <?php
                                                        date_default_timezone_set('Asia/Bangkok');
                                                        $currentDate = new DateTime();
                                                        $thaiMonths = [
                                                            'January' => 'มกราคม',
                                                            'February' => 'กุมภาพันธ์',
                                                            'March' => 'มีนาคม',
                                                            'April' => 'เมษายน',
                                                            'May' => 'พฤษภาคม',
                                                            'June' => 'มิถุนายน',
                                                            'July' => 'กรกฎาคม',
                                                            'August' => 'สิงหาคม',
                                                            'September' => 'กันยายน',
                                                            'October' => 'ตุลาคม',
                                                            'November' => 'พฤศจิกายน',
                                                            'December' => 'ธันวาคม'
                                                        ];
                                                        $monthName = $thaiMonths[$currentDate->format('F')];
                                                        $thaiYear = $currentDate->format('Y') + 543;
                                                        $thaiDateString = $currentDate->format('d') . ' ' . $monthName . ' ' . $thaiYear;
                                                        $gregorianDate = $currentDate->format('Y-m-d');
                                                        ?>
                                                        <input type="text" class="form-control" id="thai_date_display" value="<?= $thaiDateString ?>" readonly>
                                                        <input type="hidden" id="prg_date" name="prg_date" value="<?= $gregorianDate ?>" required>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="operation_number">ปฏิบัติการที่</label>
                                                        <input type="text" class="form-control" id="operation_number" name="operation_number" required>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- เจ้าหน้าที่ผู้ให้บริการ (Dropdown + Checkbox) -->
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="staff_members">เจ้าหน้าที่ผู้ให้บริการ</label>
                                                        <div class="dropdown">
                                                            <button class="form-control text-start dropdown-toggle" type="button" id="dropdownStaff" data-bs-toggle="dropdown" aria-expanded="false">
                                                                เลือกเจ้าหน้าที่
                                                            </button>
                                                            <ul class="dropdown-menu w-100 px-3" aria-labelledby="dropdownStaff" style="max-height: 250px; overflow-y: auto;">
                                                                <?php
                                                                $staffList = ['มูฮัมหมัด บากอ', 'อันวา สีดิ', 'อับดุลเลาะ มูฮัมหมัด', 'อิสมาแอ เจะมะ'];
                                                                foreach ($staffList as $index => $staff) {
                                                                    echo <<<HTML
                                                            <li>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="staff_members[]" id="staff_$index" value="$staff">
                                                                    <label class="form-check-label" for="staff_$index">$staff</label>
                                                                </div>
                                                            </li>
                                                            HTML;
                                                                }
                                                                ?>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>ผลการปฏิบัติงาน</label><br>
                                                        <div class="d-flex align-items-center">
                                                            <div class="form-check me-3">
                                                                <input class="form-check-input" type="radio" name="operation_result" id="result_found" value="พบเหตุ" required>
                                                                <label class="form-check-label" for="result_found">พบเหตุ</label>
                                                            </div>
                                                            <div class="form-check me-3">
                                                                <input class="form-check-input" type="radio" name="operation_result" id="result_not_found" value="ไม่พบเหตุ">
                                                                <label class="form-check-label" for="result_not_found">ไม่พบเหตุ</label>
                                                            </div>
                                                            <a href="#" class="clear-radio-btn" data-target="operation_result" style="display: none; margin-left: 15px; color: red; cursor: pointer;">
                                                                ล้างคำตอบ
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="incident_location">สถานที่เกิดเหตุ</label>
                                                        <input type="text" class="form-control" id="incident_location" name="incident_location">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="incident_description">เหตุการณ์</label>
                                                        <textarea class="form-control" id="incident_description" name="incident_description" rows="3"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- ส่วนที่ 2: ข้อมูลเวลา -->
                                        <div class="card-body">
                                            <div class="card-title">
                                                <h5>2. ข้อมูลเวลา</h5>
                                            </div>

                                            <div class="table-responsive">
                                                <table class="table table-bordered text-center align-middle">
                                                    <thead>
                                                        <tr>
                                                            <th rowspan="2">เวลา (น.)</th>
                                                            <th>รับแจ้ง</th>
                                                            <th>สั่งการ</th>
                                                            <th>ออกฐาน</th>
                                                            <th>ถึงที่เกิดเหตุ</th>
                                                            <th>ออกจากที่เกิดเหตุ</th>
                                                            <th>ถึง รพ.</th>
                                                            <th>กลับถึงฐาน</th>
                                                        </tr>
                                                        <tr>
                                                            <td><input type="time" class="form-control" name="receive_time"></td>
                                                            <td><input type="time" class="form-control" name="order_time"></td>
                                                            <td><input type="time" class="form-control" name="exit_base_time"></td>
                                                            <td><input type="time" class="form-control" name="arrive_scene_time"></td>
                                                            <td><input type="time" class="form-control" name="leave_scene_time"></td>
                                                            <td><input type="time" class="form-control" name="arrive_hospital_time"></td>
                                                            <td><input type="time" class="form-control" name="return_base_time"></td>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <th rowspan="2">รวมเวลา (นาที)</th>
                                                            <td colspan="4" rowspan="2">เวลาตอบสนอง =
                                                                <input type="number" class="form-control d-inline-block" name="response_time" style="width: 100px;"> นาที
                                                            </td>
                                                            <td colspan="2"><input type="number" class="form-control d-inline-block" name="total_distance_go" style="width: 100px;"> นาที</td>
                                                            <td>ช่องว่าง</td>
                                                        </tr>
                                                        <tr>
                                                            <td>ช่องว่าง</td>
                                                            <td colspan="2"><input type="number" class="form-control d-inline-block" name="total_distance_back" style="width: 100px;"> นาที</td>
                                                        </tr>
                                                        <!-- add -->
                                                        <tr>
                                                            <th>เลข กม.</th>
                                                            <td colspan="3"><input type="number" class="form-control d-inline-block" name="number_km_1">
                                                            </td>
                                                            <td colspan="2"><input type="number" class="form-control d-inline-block" name="number_km_2"></td>
                                                            <td><input type="number" class="form-control d-inline-block" name="number_km_3"></td>
                                                            <td><input type="number" class="form-control d-inline-block" name="number_km_4"></td>
                                                        </tr>
                                                        <tr>
                                                            <th rowspan="2">ระยะทาง (กม.)</th>
                                                            <td colspan="4" rowspan="2">รวมระยะทางไป
                                                                <input type="number" class="form-control d-inline-block" name="total_km_go" style="width: 100px;"> กม.
                                                            </td>
                                                            <td>ช่องว่าง</td>
                                                            <td colspan="2">ระยะทางกลับ <input type="number" class="form-control d-inline-block" name="total_km_go_home" style="width: 100px;"> กม.</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2">ระยะไป รพ. <input type="number" class="form-control d-inline-block" name="total_km_go_hosp" style="width: 100px;"> กม.</td>
                                                            <td>ช่องว่าง</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <!-- ส่วนที่ 3: ข้อมูลผู้ป่วย -->
                                        <div class="card-body">
                                            <div class="card-title">
                                                <h5>3. ข้อมูลผู้ป่วย</h5>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="patient_name">ชื่อผู้ป่วย</label>
                                                        <input type="text" class="form-control" id="patient_name" name="patient_name">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="patient_age">อายุ</label>
                                                        <input type="number" class="form-control" id="patient_age" name="patient_age">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>เพศ</label><br>
                                                        <div class="d-flex align-items-center">
                                                            <div class="form-check me-3">
                                                                <input class="form-check-input" type="radio" name="patient_gender" id="gender_male" value="ชาย">
                                                                <label class="form-check-label" for="gender_male">ชาย</label>
                                                            </div>
                                                            <div class="form-check me-3">
                                                                <input class="form-check-input" type="radio" name="patient_gender" id="gender_female" value="หญิง">
                                                                <label class="form-check-label" for="gender_female">หญิง</label>
                                                            </div>
                                                            <a href="#" class="clear-radio-btn" data-target="patient_gender" style="display: none; margin-left: 15px; color: red; cursor: pointer;">
                                                                ล้างคำตอบ
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="patient_id_card">เลขบัตรประชาชน</label>
                                                        <input type="text" class="form-control" id="patient_id_card" name="patient_id_card" maxlength="13"
                                                            oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                                        <small id="idCardError" class="text-danger d-none">กรุณากรอกเลขบัตรประชาชนให้ครบ 13 หลัก</small>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>สิทธิการรักษา</label><br>
                                                        <div class="d-flex flex-wrap align-items-center">
                                                            <div class="form-check me-3">
                                                                <input class="form-check-input" type="radio" name="treatment_rights" id="rights_gold" value="บัตรทอง">
                                                                <label class="form-check-label" for="rights_gold">บัตรทอง</label>
                                                            </div>
                                                            <div class="form-check me-3">
                                                                <input class="form-check-input" type="radio" name="treatment_rights" id="rights_officer" value="ข้าราชการ">
                                                                <label class="form-check-label" for="rights_officer">ข้าราชการ</label>
                                                            </div>
                                                            <div class="form-check me-3">
                                                                <input class="form-check-input" type="radio" name="treatment_rights" id="rights_social" value="ประกันสังคม">
                                                                <label class="form-check-label" for="rights_social">ประกันสังคม</label>
                                                            </div>
                                                            <div class="form-check me-3">
                                                                <input class="form-check-input" type="radio" name="treatment_rights" id="rights_foreign" value="แรงงานต่างด้าวขึ้นทะเบียน">
                                                                <label class="form-check-label" for="rights_foreign">แรงงานต่างด้าวขึ้นทะเบียน</label>
                                                            </div>
                                                            <div class="form-check me-3">
                                                                <input class="form-check-input" type="radio" name="treatment_rights" id="rights_none" value="ไม่มีหลักประกัน">
                                                                <label class="form-check-label" for="rights_none">ไม่มีหลักประกัน</label>
                                                            </div>
                                                            <a href="#" class="clear-radio-btn" data-target="treatment_rights" style="display: none; margin-left: 15px; color: red; cursor: pointer;">
                                                                ล้างคำตอบ
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- add -->
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>ประกันอื่น ๆ (ถ้ามี)</label><br />
                                                        <div class="d-lg-flex flex-wrap gap-3">
                                                            <div class="form-check me-3">
                                                                <input class="form-check-input" type="radio" name="other_insurance" id="insuranceLife" value="ประกันชีวิต" />
                                                                <label class="form-check-label" for="insuranceLife">ประกันชีวิต</label>
                                                            </div>
                                                            <div class="form-check me-3">
                                                                <input class="form-check-input" type="radio" name="other_insurance" id="insuranceAccident" value="ผู้ประสบภัยจากรถ" />
                                                                <label class="form-check-label" for="insuranceAccident">ผู้ประสบภัยจากรถ</label>
                                                            </div>
                                                            <a type="button" class="clear-radio-btn" data-target="other_insurance" style="display: none; margin-left: 15px; margin-top: 7px; padding: 4px 12px; font-size: 12px; color: red; cursor: pointer;">
                                                                ล้างคำตอบ
                                                            </a>
                                                        </div>

                                                        <!-- ฟอร์มกรอกทะเบียนรถ -->
                                                        <div id="plateInput" class="mt-3">
                                                            <label for="license_plate">เลขทะเบียนรถ</label>
                                                            <input type="text" class="form-control" id="license_plate" name="license_plate" placeholder="กรอกเลขทะเบียนรถ" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <script>
                                                // เมื่อโหลดหน้าและมีการเปลี่ยนตัวเลือก
                                                document.addEventListener("DOMContentLoaded", function() {
                                                    const radioLife = document.getElementById("insuranceLife");
                                                    const radioAccident = document.getElementById("insuranceAccident");
                                                    const plateInput = document.getElementById("plateInput");

                                                    function togglePlateInput() {
                                                        if (radioAccident.checked) {
                                                            plateInput.style.display = "block";
                                                        } else {
                                                            plateInput.style.display = "none";
                                                        }
                                                    }

                                                    // เรียกใช้ตอนโหลด
                                                    togglePlateInput();

                                                    // เพิ่ม event listener
                                                    radioLife.addEventListener("change", togglePlateInput);
                                                    radioAccident.addEventListener("change", togglePlateInput);
                                                });
                                            </script>
                                            <!-- close add -->

                                            <!-- ส่วนอื่นๆ ของฟอร์มผู้ป่วย (สภาพผู้ป่วย, การช่วยเหลือ) -->
                                            <!-- ... -->
                                            <div class="card-title text-center">
                                                <h5>สภาพผู้ป่วย</h5>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>ประเภทผู้ป่วย</label><br>
                                                        <div class="d-flex flex-wrap gap-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="patient_condition" id="condition_injury" value="บาดเจ็บ/อุบัติเหตุ">
                                                                <label class="form-check-label" for="condition_injury">บาดเจ็บ/อุบัติเหตุ</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="patient_condition" id="condition_emergency" value="ป่วยฉุกเฉิน">
                                                                <label class="form-check-label" for="condition_emergency">ป่วยฉุกเฉิน</label>
                                                            </div>
                                                            <a type="button" class="clear-radio-btn" data-target="patient_condition" style="display: none; margin-left: 15px; margin-top: 7px; padding: 4px 12px; font-size: 12px; color: red; cursor: pointer;">
                                                                ล้างคำตอบ
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- ตาราง Vital Signs -->
                                            <div class="table-responsive mt-3">
                                                <table class="table table-bordered text-center align-middle">
                                                    <thead>
                                                        <tr>
                                                            <th rowspan="2">Time</th>
                                                            <th colspan="4">Vital Signs</th>
                                                            <th colspan="3">Neuro Signs</th>
                                                            <th rowspan="2">DTX</th>
                                                        </tr>
                                                        <tr>
                                                            <th>T</th>
                                                            <th>BP</th>
                                                            <th>PR</th>
                                                            <th>RR</th>
                                                            <th>E</th>
                                                            <th>V</th>
                                                            <th>M</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td><input type="time" class="form-control" name="vital_time"></td>
                                                            <td><input type="text" class="form-control" name="vital_t"></td>
                                                            <td><input type="text" class="form-control" name="vital_bp"></td>
                                                            <td><input type="text" class="form-control" name="vital_pr"></td>
                                                            <td><input type="text" class="form-control" name="vital_rr"></td>
                                                            <td><input type="text" class="form-control" name="neuro_e"></td>
                                                            <td><input type="text" class="form-control" name="neuro_v"></td>
                                                            <td><input type="text" class="form-control" name="neuro_m"></td>
                                                            <td><input type="text" class="form-control" name="dtx"></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <!-- ระดับความรู้สึกตัว -->
                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>ความรู้สึกตัว</label><br>
                                                        <div class="d-flex flex-wrap gap-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="consciousness" id="conscious_alert" value="รู้สึกตัวดี">
                                                                <label class="form-check-label" for="conscious_alert">รู้สึกตัวดี</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="consciousness" id="conscious_drowsy" value="ซึม">
                                                                <label class="form-check-label" for="conscious_drowsy">ซึม</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="consciousness" id="conscious_responsive" value="หมดสติปลุกตื่น">
                                                                <label class="form-check-label" for="conscious_responsive">หมดสติปลุกตื่น</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="consciousness" id="conscious_unresponsive" value="หมดสติปลุกไม่ตื่น">
                                                                <label class="form-check-label" for="conscious_unresponsive">หมดสติปลุกไม่ตื่น</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="consciousness" id="noisy" value="เอะอะโวยวาย">
                                                                <label class="form-check-label" for="noisy">เอะอะโวยวาย</label>
                                                            </div>
                                                            <a type="button" class="clear-radio-btn" data-target="consciousness" style="display: none; margin-left: 15px; margin-top: 7px; padding: 4px 12px; font-size: 12px; color: red; cursor: pointer;">
                                                                ล้างคำตอบ
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- การหายใจ -->
                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>การหายใจ</label><br>
                                                        <div class="d-flex flex-wrap gap-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="breathing_status" id="breath_normal" value="ปกติ">
                                                                <label class="form-check-label" for="breath_normal">ปกติ</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="breathing_status" id="breath_fast" value="เร็ว">
                                                                <label class="form-check-label" for="breath_fast">เร็ว</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="breathing_status" id="breath_slow" value="ช้า">
                                                                <label class="form-check-label" for="breath_slow">ช้า</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="breathing_status" id="breath_irregular" value="ไม่สม่ำเสมอ">
                                                                <label class="form-check-label" for="breath_irregular">ไม่สม่ำเสมอ</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="breathing_status" id="not_breathing" value="ไม่หายใจ">
                                                                <label class="form-check-label" for="not_breathing">ไม่หายใจ</label>
                                                            </div>
                                                            <a href="#" class="clear-radio-btn" data-target="breathing_status" style="display: none; margin-left: 15px; margin-top: 7px; padding: 4px 12px; font-size: 12px; color: red; cursor: pointer;">
                                                                ล้างคำตอบ
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- บาดแผล -->
                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>บาดแผล</label><br>
                                                        <div class="d-flex flex-wrap gap-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="wound_type" id="wound1" value="ไม่มี">
                                                                <label class="form-check-label" for="wound1">ไม่มี</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="wound_type" id="wound2" value="แผลถลอก">
                                                                <label class="form-check-label" for="wound2">แผลถลอก</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="wound_type" id="wound3" value="ฉีกขาด/ตัด">
                                                                <label class="form-check-label" for="wound3">ฉีกขาด/ตัด</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="wound_type" id="wound4" value="แผลฟกช้ำ">
                                                                <label class="form-check-label" for="wound4">แผลฟกช้ำ</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="wound_type" id="wound5" value="แผลไหม้">
                                                                <label class="form-check-label" for="wound5">แผลไหม้</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="wound_type" id="wound6" value="ถูกยิง">
                                                                <label class="form-check-label" for="wound6">ถูกยิง</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="wound_type" id="wound7" value="ถูกแทง">
                                                                <label class="form-check-label" for="wound7">ถูกแทง</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="wound_type" id="wound8" value="อวัยวะตัดขาด">
                                                                <label class="form-check-label" for="wound8">อวัยวะตัดขาด</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="wound_type" id="wound9" value="ถูกระเบิด">
                                                                <label class="form-check-label" for="wound9">ถูกระเบิด</label>
                                                            </div>
                                                            <a href="#" class="clear-radio-btn" data-target="wound_type" style="display: none; margin-left: 15px; margin-top: 7px; padding: 4px 12px; font-size: 12px; color: red; cursor: pointer;">
                                                                ล้างคำตอบ
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- กระดูกผิดรูป -->
                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>กระดูกผิดรูป</label><br>
                                                        <div class="d-flex flex-wrap gap-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="bone_status" id="bone1" value="ไม่มี">
                                                                <label class="form-check-label" for="bone1">ไม่มี</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="bone_status" id="bone2" value="ผิดรูป">
                                                                <label class="form-check-label" for="bone2">ผิดรูป</label>
                                                            </div>
                                                            <a href="#" class="clear-radio-btn" data-target="bone_status" style="display: none; margin-left: 15px; margin-top: 7px; padding: 4px 12px; font-size: 12px; color: red; cursor: pointer;">
                                                                ล้างคำตอบ
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- อวัยวะ -->
                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>อวัยวะ</label><br>
                                                        <div class="d-flex flex-wrap gap-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="affected_organ" id="affected_organ1" value="ศีรษะ/คอ">
                                                                <label class="form-check-label" for="affected_organ1">ศีรษะ/คอ</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="affected_organ" id="affected_organ2" value="ใบหน้า">
                                                                <label class="form-check-label" for="affected_organ2">ใบหน้า</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="affected_organ" id="affected_organ3" value="สันหลัง/หลัง">
                                                                <label class="form-check-label" for="affected_organ3">สันหลัง/หลัง</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="affected_organ" id="affected_organ4" value=" หน้าอก/ไหปลาร้า">
                                                                <label class="form-check-label" for="affected_organ4">หน้าอก/ไหปลาร้า</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="affected_organ" id="affected_organ5" value="ช่องท้อง">
                                                                <label class="form-check-label" for="affected_organ5">ช่องท้อง</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="affected_organ" id="affected_organ6" value="เชิงกราน">
                                                                <label class="form-check-label" for="affected_organ6">เชิงกราน</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="affected_organ" id="affected_organ7" value="Extremities">
                                                                <label class="form-check-label" for="affected_organ7">Extremities</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="affected_organ" id="affected_organ8" value="ผิวหนัง">
                                                                <label class="form-check-label" for="affected_organ8">ผิวหนัง</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="affected_organ" id="affected_organ9" value="Multiple injury back">
                                                                <label class="form-check-label" for="affected_organ9">Multiple injury back</label>
                                                            </div>
                                                            <a href="#" class="clear-radio-btn" data-target="affected_organ" style="display: none; margin-left: 15px; margin-top: 7px; padding: 4px 12px; font-size: 12px; color: red; cursor: pointer;">
                                                                ล้างคำตอบ
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- ส่วนที่ 3.2: การช่วยเหลือ -->
                                        <div class="card-body">
                                            <div class="card-title">
                                                <h5>การช่วยเหลือ</h5>
                                            </div>

                                            <!-- ทางเดินหายใจ/การหายใจ -->
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>ทางเดินหายใจ/การหายใจ</label><br>
                                                        <div class="d-flex flex-wrap gap-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="airway_assistance" id="airway_none" value="ไม่">
                                                                <label class="form-check-label" for="airway_none">ไม่</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="airway_assistance" id="airway_open" value="เปิดทางเดินหายใจ">
                                                                <label class="form-check-label" for="airway_open">เปิดทางเดินหายใจ</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="airway_assistance" id="airway_oral" value="ใส่ Oral airway">
                                                                <label class="form-check-label" for="airway_oral">ใส่ Oral airway</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="airway_assistance" id="airway_oral1" value="ให้ 02 canula/mask">
                                                                <label class="form-check-label" for="airway_oral1">ให้ 02 canula/mask</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="airway_assistance" id="airway_oral2" value="Ambu bag">
                                                                <label class="form-check-label" for="airway_oral2">Ambu bag</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="airway_assistance" id="airway_oral3" value="Pocket Mask">
                                                                <label class="form-check-label" for="airway_oral3">Pocket Mask</label>
                                                            </div>
                                                            <a href="#" class="clear-radio-btn" data-target="airway_assistance" style="display: none; margin-left: 15px; margin-top: 7px; padding: 4px 12px; font-size: 12px; color: red; cursor: pointer;">
                                                                ล้างคำตอบ
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- บาดแผล/ห้ามเลือด -->
                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>บาดแผล/ห้ามเลือด</label><br>
                                                        <div class="d-flex flex-wrap gap-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="wound_care" id="wound_none" value="ไม่">
                                                                <label class="form-check-label" for="wound_none">ไม่</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="wound_care" id="wound_pressure" value="การกดห้ามเลือด">
                                                                <label class="form-check-label" for="wound_pressure">การกดห้ามเลือด</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="wound_care" id="wound_dressing" value="ทำแผล">
                                                                <label class="form-check-label" for="wound_dressing">ทำแผล</label>
                                                            </div>
                                                            <a href="#" class="clear-radio-btn" data-target="wound_care" style="display: none; margin-left: 15px; margin-top: 7px; padding: 4px 12px; font-size: 12px; color: red; cursor: pointer;">
                                                                ล้างคำตอบ
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- การดามกระดูก -->
                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>การดามกระดูก</label><br>
                                                        <div class="d-flex flex-wrap gap-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="bone_immobilization" id="bone_none" value="ไม่">
                                                                <label class="form-check-label" for="bone_none">ไม่</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="bone_immobilization" id="bone_splint" value="เฝือกลม/ไม้ดาม/sling">
                                                                <label class="form-check-label" for="bone_splint">เฝือกลม/ไม้ดาม/sling</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="bone_immobilization" id="bone_none2" value="เฝือกดามคอและกระดานรองหลังยาว">
                                                                <label class="form-check-label" for="bone_none2">เฝือกดามคอและกระดานรองหลังยาว</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="bone_immobilization" id="bone_none3" value="เฝือกหลังและคอ (KED)">
                                                                <label class="form-check-label" for="bone_none3">เฝือกหลังและคอ (KED)</label>
                                                            </div>
                                                            <a href="#" class="clear-radio-btn" data-target="bone_immobilization" style="display: none; margin-left: 15px; margin-top: 7px; padding: 4px 12px; font-size: 12px; color: red; cursor: pointer;">
                                                                ล้างคำตอบ
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- ช่วยฟื้นคืนชีพ -->
                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>ช่วยฟื้นคืนชีพ</label><br>
                                                        <div class="d-flex flex-wrap gap-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="resuscitation" id="resuscitation1" value="ไม่ได้ทำ">
                                                                <label class="form-check-label" for="resuscitation1">ไม่ได้ทำ</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="resuscitation" id="resuscitation2" value="ทำ">
                                                                <label class="form-check-label" for="resuscitation2">ทำ</label>
                                                            </div>
                                                            <a href="#" class="clear-radio-btn" data-target="resuscitation" style="display: none; margin-left: 15px; margin-top: 7px; padding: 4px 12px; font-size: 12px; color: red; cursor: pointer;">
                                                                ล้างคำตอบ
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- ผลการดูแลรักษาขั้นต้น -->
                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>ผลการดูแลรักษาขั้นต้น</label><br>
                                                        <div class="d-flex flex-wrap gap-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="treatment_result" id="result_refuse" value="ไม่ยอมให้รักษา">
                                                                <label class="form-check-label" for="result_refuse">ไม่ยอมให้รักษา</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="treatment_result" id="result_improved" value="ทุเลา">
                                                                <label class="form-check-label" for="result_improved">ทุเลา</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="treatment_result" id="result_stable" value="คงเดิม/คงที่">
                                                                <label class="form-check-label" for="result_stable">คงเดิม/คงที่</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="treatment_result" id="result_stable2" value="ทรุดหนัก">
                                                                <label class="form-check-label" for="result_stable2">ทรุดหนัก</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="treatment_result" id="result_stable3" value="เสียชีวิต ณ จุดเกิดเหตุ">
                                                                <label class="form-check-label" for="result_stable3">เสียชีวิต ณ จุดเกิดเหตุ</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="treatment_result" id="result_stable4" value="เสียชีวิตขณะนำส่ง">
                                                                <label class="form-check-label" for="result_stable4">เสียชีวิตขณะนำส่ง</label>
                                                            </div>
                                                            <a href="#" class="clear-radio-btn" data-target="treatment_result" style="display: none; margin-left: 15px; margin-top: 7px; padding: 4px 12px; font-size: 12px; color: red; cursor: pointer;">
                                                                ล้างคำตอบ
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <!-- ส่วนที่ 4: เกณฑ์การตัดสินใจส่งโรงบาล -->
                                        <div class="card-body">
                                            <div class="card-title">
                                                <h5>4. เกณฑ์การตัดสินใจส่งโรงบาล</h5>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="hospital_destination">นำส่งห้องฉุกเฉินโรงพยาบาล</label>
                                                        <input type="text" class="form-control" id="hospital_destination" name="hospital_destination">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>ประเภทโรงพยาบาล</label><br>
                                                        <div class="d-flex align-items-center">
                                                            <div class="form-check me-3">
                                                                <input class="form-check-input" type="radio" name="hospital_type" id="hospital_government" value="รพ.รัฐ">
                                                                <label class="form-check-label" for="hospital_government">รพ.รัฐ</label>
                                                            </div>
                                                            <div class="form-check me-3">
                                                                <input class="form-check-input" type="radio" name="hospital_type" id="hospital_private" value="รพ.เอกชน">
                                                                <label class="form-check-label" for="hospital_private">รพ.เอกชน</label>
                                                            </div>
                                                            <a href="#" class="clear-radio-btn" data-target="hospital_type" style="display: none; margin-left: 15px; padding: 4px 12px; font-size: 12px; color: red; cursor: pointer;">
                                                                ล้างคำตอบ
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>เหตุผล (เลือกได้มากกว่า 1 ข้อ)</label><br>
                                                        <div class="d-flex flex-wrap">
                                                            <div class="form-check me-3 mb-2">
                                                                <input class="form-check-input" type="checkbox" name="reasons[]" id="reason_suitable" value="เหมาะสม/สามารถรักษาได้">
                                                                <label class="form-check-label" for="reason_suitable">เหมาะสม/สามารถรักษาได้</label>
                                                            </div>
                                                            <div class="form-check me-3 mb-2">
                                                                <input class="form-check-input" type="checkbox" name="reasons[]" id="reason_nearby" value="อยู่ใกล้">
                                                                <label class="form-check-label" for="reason_nearby">อยู่ใกล้</label>
                                                            </div>
                                                            <div class="form-check me-3 mb-2">
                                                                <input class="form-check-input" type="checkbox" name="reasons[]" id="reason_insurance" value="มีหลักประกัน">
                                                                <label class="form-check-label" for="reason_insurance">มีหลักประกัน</label>
                                                            </div>
                                                            <div class="form-check me-3 mb-2">
                                                                <input class="form-check-input" type="checkbox" name="reasons[]" id="reason_old_patient" value="เป็นผู้ป่วยเก่า">
                                                                <label class="form-check-label" for="reason_old_patient">เป็นผู้ป่วยเก่า</label>
                                                            </div>
                                                            <div class="form-check me-3 mb-2">
                                                                <input class="form-check-input" type="checkbox" name="reasons[]" id="reason_preference" value="เป็นความประสงค์">
                                                                <label class="form-check-label" for="reason_preference">เป็นความประสงค์</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="report_summarizer">ผู้สรุปรายงาน</label>
                                                        <select class="form-control" id="report_summarizer" name="report_summarizer">
                                                            <option value="">เลือกผู้สรุปรายงาน</option>
                                                            <option value="มูฮัมหมัด บากอ">มูฮัมหมัด บากอ</option>
                                                            <option value="อันวา สีดิ">อันวา สีดิ</option>
                                                            <option value="อับดุลเลาะ มูฮัมหมัด">อับดุลเลาะ มูฮัมหมัด</option>
                                                            <option value="อิสมาแอ เจะมะ">อิสมาแอ เจะมะ</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card-action">
                                            <button type="submit" name="save" class="btn btn-success">บันทึก</button>
                                            <a href="index.php" class="btn btn-danger">ย้อนกลับ</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php }
                if (isset($_GET['edit'])) {
                    $id = $_GET['edit'];
                    $incident = getIncidentById($pdo, $id);
                    if (!$incident) {
                        $_SESSION['error'] = "ไม่พบข้อมูลที่ต้องการแก้ไข";
                        header("Location: ../event/");
                        exit();
                    }
                    ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <?php
                                    if (isset($_POST['edit'])) {
                                        if (isset($_SESSION['error'])) {
                                            echo '
                        <div class="alert alert-danger col-8 mx-auto text-center p-2 border rounded text-center">' . $_SESSION['error'] . '</div>
                        ';
                                            unset($_SESSION['error']);
                                        }
                                    }
                                    ?>

                                    <form role="form" method="post" action="index.php" enctype="multipart/form-data">
                                        <input type="hidden" name="id" value="<?= $incident['id'] ?>">

                                        <div class="card-header">
                                            <div class="card-title">แบบฟอร์มแก้ไขพื้นที่ออกเหตุและเวลา</div>
                                        </div>

                                        <!-- ส่วนที่ 1: ข้อมูลหน่วยงาน -->
                                        <div class="card-body">
                                            <div class="card-title">
                                                <h5>1. หน่วยงาน</h5>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="unit_name">ชื่อหน่วยบริการ</label>
                                                        <input type="text" class="form-control" id="unit_name" name="unit_name"
                                                            value="<?= htmlspecialchars($incident['unit_name']) ?>" required>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="prg_date">วันที่บันทึก</label>
                                                        <?php
                                                        $recordDate = new DateTime($incident['record_date']);
                                                        $thaiMonths = [
                                                            'January' => 'มกราคม',
                                                            'February' => 'กุมภาพันธ์',
                                                            'March' => 'มีนาคม',
                                                            'April' => 'เมษายน',
                                                            'May' => 'พฤษภาคม',
                                                            'June' => 'มิถุนายน',
                                                            'July' => 'กรกฎาคม',
                                                            'August' => 'สิงหาคม',
                                                            'September' => 'กันยายน',
                                                            'October' => 'ตุลาคม',
                                                            'November' => 'พฤศจิกายน',
                                                            'December' => 'ธันวาคม'
                                                        ];
                                                        $thaiDateString = $recordDate->format('d') . ' ' . $thaiMonths[$recordDate->format('F')] . ' ' . ($recordDate->format('Y') + 543);
                                                        ?>
                                                        <input type="text" class="form-control" id="thai_date_display" value="<?= $thaiDateString ?>" readonly>
                                                        <input type="hidden" id="prg_date" name="prg_date" value="<?= $incident['record_date'] ?>" required>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="operation_number">ปฏิบัติการที่</label>
                                                        <input type="text" class="form-control" id="operation_number" name="operation_number"
                                                            value="<?= htmlspecialchars($incident['operation_number']) ?>" required>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="staff_members">เจ้าหน้าที่ผู้ให้บริการ</label>
                                                        <div class="dropdown">
                                                            <button class="form-control text-start dropdown-toggle" type="button" id="dropdownStaff" data-bs-toggle="dropdown" aria-expanded="false">
                                                                เลือกเจ้าหน้าที่
                                                            </button>
                                                            <ul class="dropdown-menu w-100 px-3" aria-labelledby="dropdownStaff" style="max-height: 250px; overflow-y: auto;">
                                                                <?php
                                                                $staffList = ['มูฮัมหมัด บากอ', 'อันวา สีดิ', 'อับดุลเลาะ มูฮัมหมัด', 'อิสมาแอ เจะมะ'];
                                                                $selectedStaff = isset($incident['staff_members']) ? explode(', ', $incident['staff_members']) : [];
                                                                foreach ($staffList as $index => $staff) {
                                                                    $checked = in_array($staff, $selectedStaff) ? 'checked' : '';
                                                                    echo <<<HTML
                                                                <li>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="checkbox" name="staff_members[]" id="staff_$index" value="$staff" $checked>
                                                                        <label class="form-check-label" for="staff_$index">$staff</label>
                                                                    </div>
                                                                </li>
                                                                HTML;
                                                                }
                                                                ?>
                                                            </ul>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>ผลการปฏิบัติงาน</label><br>
                                                        <div class="d-flex align-items-center">
                                                            <div class="form-check me-3">
                                                                <input class="form-check-input" type="radio" name="operation_result" id="result_found" value="พบเหตุ" <?= $incident['operation_result'] === 'พบเหตุ' ? 'checked' : '' ?> required>
                                                                <label class="form-check-label" for="result_found">พบเหตุ</label>
                                                            </div>
                                                            <div class="form-check me-3">
                                                                <input class="form-check-input" type="radio" name="operation_result" id="result_not_found" value="ไม่พบเหตุ" <?= $incident['operation_result'] === 'ไม่พบเหตุ' ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="result_not_found">ไม่พบเหตุ</label>
                                                            </div>
                                                            <a href="#" class="clear-radio-btn" data-target="operation_result" style="display: none; margin-left: 15px; color: red; cursor: pointer;">
                                                                ล้างคำตอบ
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="incident_location">สถานที่เกิดเหตุ</label>
                                                        <input type="text" class="form-control" id="incident_location" name="incident_location" value="<?= htmlspecialchars($incident['incident_location']) ?>" required>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="incident_description">เหตุการณ์</label>
                                                        <textarea class="form-control" id="incident_description" name="incident_description" rows="3"><?= htmlspecialchars($incident['incident_description']) ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- ส่วนที่ 2: ข้อมูลเวลา -->
                                        <div class="card-body">
                                            <div class="card-title">
                                                <h5>2. ข้อมูลเวลา</h5>
                                            </div>

                                            <div class="table-responsive">
                                                <table class="table table-bordered text-center align-middle">
                                                    <thead>
                                                        <tr>
                                                            <th rowspan="2">เวลา (น.)</th>
                                                            <th>รับแจ้ง</th>
                                                            <th>สั่งการ</th>
                                                            <th>ออกฐาน</th>
                                                            <th>ถึงที่เกิดเหตุ</th>
                                                            <th>ออกจากที่เกิดเหตุ</th>
                                                            <th>ถึง รพ.</th>
                                                            <th>กลับถึงฐาน</th>
                                                        </tr>
                                                        <tr>
                                                            <td><input type="time" class="form-control" name="receive_time" value="<?= $incident['receive_time'] ?>"></td>
                                                            <td><input type="time" class="form-control" name="order_time" value="<?= $incident['order_time'] ?>"></td>
                                                            <td><input type="time" class="form-control" name="exit_base_time" value="<?= $incident['exit_base_time'] ?>"></td>
                                                            <td><input type="time" class="form-control" name="arrive_scene_time" value="<?= $incident['arrive_scene_time'] ?>"></td>
                                                            <td><input type="time" class="form-control" name="leave_scene_time" value="<?= $incident['leave_scene_time'] ?>"></td>
                                                            <td><input type="time" class="form-control" name="arrive_hospital_time" value="<?= $incident['arrive_hospital_time'] ?>"></td>
                                                            <td><input type="time" class="form-control" name="return_base_time" value="<?= $incident['return_base_time'] ?>"></td>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <th rowspan="2">รวมเวลา (นาที)</th>
                                                            <td colspan="4" rowspan="2">เวลาตอบสนอง =
                                                                <input type="number" class="form-control d-inline-block" name="response_time" value="<?= $incident['response_time'] ?>" style="width: 100px;"> นาที
                                                            </td>
                                                            <td colspan="2"><input type="number" class="form-control d-inline-block" name="total_distance_go" value="<?= $incident['total_distance_go'] ?>" style="width: 100px;"> นาที</td>
                                                            <td>ช่องว่าง</td>
                                                        </tr>
                                                        <tr>
                                                            <td>ช่องว่าง</td>
                                                            <td colspan="2"><input type="number" class="form-control d-inline-block" name="total_distance_back" value="<?= $incident['total_distance_back'] ?>" style="width: 100px;"> นาที</td>
                                                        </tr>
                                                        <!-- add -->
                                                        <tr>
                                                            <th>เลข กม.</th>
                                                            <td colspan="3"><input type="number" class="form-control d-inline-block" name="number_km_1" value="<?= $incident['number_km_1'] ?>">
                                                            </td>
                                                            <td colspan="2"><input type="number" class="form-control d-inline-block" name="number_km_2" value="<?= $incident['number_km_2'] ?>"></td>
                                                            <td><input type="number" class="form-control d-inline-block" name="number_km_3" value="<?= $incident['number_km_3'] ?>"></td>
                                                            <td><input type="number" class="form-control d-inline-block" name="number_km_4" value="<?= $incident['number_km_4'] ?>"></td>
                                                        </tr>
                                                        <tr>
                                                            <th rowspan="2">ระยะทาง (กม.)</th>
                                                            <td colspan="4" rowspan="2">รวมระยะทางไป
                                                                <input type="number" class="form-control d-inline-block" name="total_km_go" value="<?= $incident['total_km_go'] ?>" style="width: 100px;"> กม.
                                                            </td>
                                                            <td>ช่องว่าง</td>
                                                            <td colspan="2">ระยะทางกลับ <input type="number" class="form-control d-inline-block" name="total_km_go_home" value="<?= $incident['total_km_go_home'] ?>" style="width: 100px;"> กม.</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2">ระยะไป รพ. <input type="number" class="form-control d-inline-block" name="total_km_go_hosp" value="<?= $incident['total_km_go_hosp'] ?>" style="width: 100px;"> กม.</td>
                                                            <td>ช่องว่าง</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <!-- ส่วนที่ 3: ข้อมูลผู้ป่วย -->
                                        <div class="card-body">
                                            <div class="card-title">
                                                <h5>3. ข้อมูลผู้ป่วย</h5>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="patient_name">ชื่อผู้ป่วย</label>
                                                        <input type="text" class="form-control" id="patient_name" name="patient_name" value="<?= htmlspecialchars($incident['patient_name']) ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="patient_age">อายุ</label>
                                                        <input type="number" class="form-control" id="patient_age" name="patient_age" value="<?= htmlspecialchars($incident['patient_age']) ?>">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>เพศ</label><br>
                                                        <div class="d-flex align-items-center">
                                                            <div class="form-check me-3">
                                                                <input type="radio" name="patient_gender" value="ชาย" <?= $incident['patient_gender'] === 'ชาย' ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="gender_male">ชาย</label>
                                                            </div>
                                                            <div class="form-check me-3">
                                                                <input type="radio" name="patient_gender" value="หญิง" <?= $incident['patient_gender'] === 'หญิง' ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="gender_female">หญิง</label>
                                                            </div>
                                                            <a href="#" class="clear-radio-btn" data-target="patient_gender" style="display: none; margin-left: 15px; color: red; cursor: pointer;">
                                                                ล้างคำตอบ
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="patient_id_card">เลขบัตรประชาชน</label>
                                                        <input type="text" class="form-control" id="patient_id_card" name="patient_id_card" maxlength="13" value="<?= htmlspecialchars($incident['patient_id_card']) ?>"
                                                            oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                                        <small id="idCardError" class="text-danger d-none">กรุณากรอกเลขบัตรประชาชนให้ครบ 13 หลัก</small>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>สิทธิการรักษา</label><br>
                                                        <div class="d-flex flex-wrap align-items-center">
                                                            <div class="form-check me-3">
                                                                <input class="form-check-input" type="radio" name="treatment_rights" id="rights_gold" value="บัตรทอง" <?= $incident['treatment_rights'] === 'บัตรทอง' ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="rights_gold">บัตรทอง</label>
                                                            </div>
                                                            <div class="form-check me-3">
                                                                <input class="form-check-input" type="radio" name="treatment_rights" id="rights_officer" value="ข้าราชการ" <?= $incident['treatment_rights'] === 'ข้าราชการ' ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="rights_officer">ข้าราชการ</label>
                                                            </div>
                                                            <div class="form-check me-3">
                                                                <input class="form-check-input" type="radio" name="treatment_rights" id="rights_social" value="ประกันสังคม" <?= $incident['treatment_rights'] === 'ประกันสังคม' ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="rights_social">ประกันสังคม</label>
                                                            </div>
                                                            <div class="form-check me-3">
                                                                <input class="form-check-input" type="radio" name="treatment_rights" id="rights_foreign" value="แรงงานต่างด้าวขึ้นทะเบียน" <?= $incident['treatment_rights'] === 'แรงงานต่างด้าวขึ้นทะเบียน' ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="rights_foreign">แรงงานต่างด้าวขึ้นทะเบียน</label>
                                                            </div>
                                                            <div class="form-check me-3">
                                                                <input class="form-check-input" type="radio" name="treatment_rights" id="rights_none" value="ไม่มีหลักประกัน" <?= $incident['treatment_rights'] === 'ไม่มีหลักประกัน' ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="rights_none">ไม่มีหลักประกัน</label>
                                                            </div>
                                                            <a href="#" class="clear-radio-btn" data-target="treatment_rights" style="display: none; margin-left: 15px; color: red; cursor: pointer;">
                                                                ล้างคำตอบ
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- add -->
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>ประกันอื่น ๆ (ถ้ามี)</label><br />
                                                        <div class="d-lg-flex flex-wrap gap-3">
                                                            <div class="form-check me-3">
                                                                <input class="form-check-input" type="radio" name="other_insurance" id="insuranceLife" value="ประกันชีวิต" <?= $incident['other_insurance'] === 'ประกันชีวิต' ? 'checked' : '' ?> />
                                                                <label class="form-check-label" for="insuranceLife">ประกันชีวิต</label>
                                                            </div>
                                                            <div class="form-check me-3">
                                                                <input class="form-check-input" type="radio" name="other_insurance" id="insuranceAccident" value="ผู้ประสบภัยจากรถ" <?= $incident['other_insurance'] === 'ผู้ประสบภัยจากรถ' ? 'checked' : '' ?> />
                                                                <label class="form-check-label" for="insuranceAccident">ผู้ประสบภัยจากรถ</label>
                                                            </div>
                                                            <a type="button" class="clear-radio-btn" data-target="other_insurance" style="display: none; margin-left: 15px; margin-top: 7px; padding: 4px 12px; font-size: 12px; color: red; cursor: pointer;">
                                                                ล้างคำตอบ
                                                            </a>
                                                        </div>

                                                        <!-- ฟอร์มกรอกทะเบียนรถ -->
                                                        <div id="plateInput" class="mt-3">
                                                            <label for="license_plate">เลขทะเบียนรถ</label>
                                                            <input type="text" class="form-control" id="license_plate" name="license_plate" placeholder="กรอกเลขทะเบียนรถ" value="<?= htmlspecialchars($incident['license_plate']) ?>" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <script>
                                                // เมื่อโหลดหน้าและมีการเปลี่ยนตัวเลือก
                                                document.addEventListener("DOMContentLoaded", function() {
                                                    const radioLife = document.getElementById("insuranceLife");
                                                    const radioAccident = document.getElementById("insuranceAccident");
                                                    const plateInput = document.getElementById("plateInput");

                                                    function togglePlateInput() {
                                                        if (radioAccident.checked) {
                                                            plateInput.style.display = "block";
                                                        } else {
                                                            plateInput.style.display = "none";
                                                        }
                                                    }

                                                    // เรียกใช้ตอนโหลด
                                                    togglePlateInput();

                                                    // เพิ่ม event listener
                                                    radioLife.addEventListener("change", togglePlateInput);
                                                    radioAccident.addEventListener("change", togglePlateInput);
                                                });
                                            </script>
                                            <!-- close add -->

                                            <!-- ส่วนอื่นๆ ของฟอร์มผู้ป่วย (สภาพผู้ป่วย, การช่วยเหลือ) -->
                                            <!-- ... -->
                                            <div class="card-title text-center">
                                                <h5>สภาพผู้ป่วย</h5>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>ประเภทผู้ป่วย</label><br>
                                                        <div class="d-flex flex-wrap gap-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="patient_condition" id="condition_injury" value="บาดเจ็บ/อุบัติเหตุ" <?= $incident['patient_condition'] === 'บาดเจ็บ/อุบัติเหตุ' ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="condition_injury">บาดเจ็บ/อุบัติเหตุ</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="patient_condition" id="condition_emergency" value="ป่วยฉุกเฉิน" <?= $incident['patient_condition'] === 'ป่วยฉุกเฉิน' ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="condition_emergency">ป่วยฉุกเฉิน</label>
                                                            </div>
                                                            <a type="button" class="clear-radio-btn" data-target="patient_condition" style="display: none; margin-left: 15px; margin-top: 7px; padding: 4px 12px; font-size: 12px; color: red; cursor: pointer;">
                                                                ล้างคำตอบ
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- ตาราง Vital Signs -->
                                            <div class="table-responsive mt-3">
                                                <table class="table table-bordered text-center align-middle">
                                                    <thead>
                                                        <tr>
                                                            <th rowspan="2">Time</th>
                                                            <th colspan="4">Vital Signs</th>
                                                            <th colspan="3">Neuro Signs</th>
                                                            <th rowspan="2">DTX</th>
                                                        </tr>
                                                        <tr>
                                                            <th>T</th>
                                                            <th>BP</th>
                                                            <th>PR</th>
                                                            <th>RR</th>
                                                            <th>E</th>
                                                            <th>V</th>
                                                            <th>M</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td><input type="time" class="form-control" name="vital_time" value="<?= $incident['vital_time'] ?>"></td>
                                                            <td><input type="text" class="form-control" name="vital_t" value="<?= $incident['vital_t'] ?>"></td>
                                                            <td><input type="text" class="form-control" name="vital_bp" value="<?= $incident['vital_bp'] ?>"></td>
                                                            <td><input type="text" class="form-control" name="vital_pr" value="<?= $incident['vital_pr'] ?>"></td>
                                                            <td><input type="text" class="form-control" name="vital_rr" value="<?= $incident['vital_rr'] ?>"></td>
                                                            <td><input type="text" class="form-control" name="neuro_e" value="<?= $incident['neuro_e'] ?>"></td>
                                                            <td><input type="text" class="form-control" name="neuro_v" value="<?= $incident['neuro_v'] ?>"></td>
                                                            <td><input type="text" class="form-control" name="neuro_m" value="<?= $incident['neuro_m'] ?>"></td>
                                                            <td><input type="text" class="form-control" name="dtx" value="<?= $incident['dtx'] ?>"></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <!-- ระดับความรู้สึกตัว -->
                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>ความรู้สึกตัว</label><br>
                                                        <div class="d-flex flex-wrap gap-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="consciousness" id="conscious_alert" value="รู้สึกตัวดี" <?= $incident['consciousness'] === 'รู้สึกตัวดี' ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="conscious_alert">รู้สึกตัวดี</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="consciousness" id="conscious_drowsy" value="ซึม" <?= $incident['consciousness'] === 'ซึม' ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="conscious_drowsy">ซึม</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="consciousness" id="conscious_responsive" value="หมดสติปลุกตื่น" <?= $incident['consciousness'] === 'หมดสติปลุกตื่น' ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="conscious_responsive">หมดสติปลุกตื่น</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="consciousness" id="conscious_unresponsive" value="หมดสติปลุกไม่ตื่น" <?= $incident['consciousness'] === 'หมดสติปลุกไม่ตื่น' ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="conscious_unresponsive">หมดสติปลุกไม่ตื่น</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="consciousness" id="noisy" value="เอะอะโวยวาย" <?= $incident['consciousness'] === 'เอะอะโวยวาย' ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="noisy">เอะอะโวยวาย</label>
                                                            </div>
                                                            <a type="button" class="clear-radio-btn" data-target="consciousness" style="display: none; margin-left: 15px; margin-top: 7px; padding: 4px 12px; font-size: 12px; color: red; cursor: pointer;">
                                                                ล้างคำตอบ
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- การหายใจ -->
                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>การหายใจ</label><br>
                                                        <div class="d-flex flex-wrap gap-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="breathing_status" id="breath_normal" value="ปกติ" <?= $incident['breathing_status'] === 'ปกติ' ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="breath_normal">ปกติ</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="breathing_status" id="breath_fast" value="เร็ว" <?= $incident['breathing_status'] === 'เร็ว' ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="breath_fast">เร็ว</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="breathing_status" id="breath_slow" value="ช้า" <?= $incident['breathing_status'] === 'ช้า' ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="breath_slow">ช้า</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="breathing_status" id="breath_irregular" value="ไม่สม่ำเสมอ" <?= $incident['breathing_status'] === 'ไม่สม่ำเสมอ' ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="breath_irregular">ไม่สม่ำเสมอ</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="breathing_status" id="not_breathing" value="ไม่หายใจ" <?= $incident['breathing_status'] === 'ไม่หายใจ' ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="not_breathing">ไม่หายใจ</label>
                                                            </div>
                                                            <a href="#" class="clear-radio-btn" data-target="breathing_status" style="display: none; margin-left: 15px; margin-top: 7px; padding: 4px 12px; font-size: 12px; color: red; cursor: pointer;">
                                                                ล้างคำตอบ
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- บาดแผล -->
                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>บาดแผล</label><br>
                                                        <div class="d-flex flex-wrap gap-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="wound_type" id="wound1" value="ไม่มี" <?= $incident['wound_type'] === 'ไม่มี' ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="wound1">ไม่มี</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="wound_type" id="wound2" value="แผลถลอก" <?= $incident['wound_type'] === 'แผลถลอก' ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="wound2">แผลถลอก</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="wound_type" id="wound3" value="ฉีกขาด/ตัด" <?= $incident['wound_type'] === 'ฉีกขาด/ตัด' ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="wound3">ฉีกขาด/ตัด</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="wound_type" id="wound4" value="แผลฟกช้ำ" <?= $incident['wound_type'] === 'แผลฟกช้ำ' ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="wound4">แผลฟกช้ำ</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="wound_type" id="wound5" value="แผลไหม้" <?= $incident['wound_type'] === 'แผลไหม้' ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="wound5">แผลไหม้</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="wound_type" id="wound6" value="ถูกยิง" <?= $incident['wound_type'] === 'ถูกยิง' ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="wound6">ถูกยิง</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="wound_type" id="wound7" value="ถูกแทง" <?= $incident['wound_type'] === 'ถูกแทง' ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="wound7">ถูกแทง</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="wound_type" id="wound8" value="อวัยวะตัดขาด" <?= $incident['wound_type'] === 'อวัยวะตัดขาด' ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="wound8">อวัยวะตัดขาด</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="wound_type" id="wound9" value="ถูกระเบิด" <?= $incident['wound_type'] === 'ถูกระเบิด' ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="wound9">ถูกระเบิด</label>
                                                            </div>
                                                            <a href="#" class="clear-radio-btn" data-target="wound_type" style="display: none; margin-left: 15px; margin-top: 7px; padding: 4px 12px; font-size: 12px; color: red; cursor: pointer;">
                                                                ล้างคำตอบ
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- กระดูกผิดรูป -->
                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>กระดูกผิดรูป</label><br>
                                                        <div class="d-flex flex-wrap gap-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="bone_status" id="bone1" value="ไม่มี" <?= $incident['bone_status'] === 'ไม่มี' ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="bone1">ไม่มี</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="bone_status" id="bone2" value="ผิดรูป" <?= $incident['bone_status'] === 'ผิดรูป' ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="bone2">ผิดรูป</label>
                                                            </div>
                                                            <a href="#" class="clear-radio-btn" data-target="bone_status" style="display: none; margin-left: 15px; margin-top: 7px; padding: 4px 12px; font-size: 12px; color: red; cursor: pointer;">
                                                                ล้างคำตอบ
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- อวัยวะ -->
                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>อวัยวะ</label><br>
                                                        <div class="d-flex flex-wrap gap-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="affected_organ" id="affected_organ1" value="ศีรษะ/คอ" <?= $incident['affected_organ'] === 'ศีรษะ/คอ' ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="affected_organ1">ศีรษะ/คอ</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="affected_organ" id="affected_organ2" value="ใบหน้า" <?= $incident['affected_organ'] === 'ใบหน้า' ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="affected_organ2">ใบหน้า</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="affected_organ" id="affected_organ3" value="สันหลัง/หลัง" <?= $incident['affected_organ'] === 'สันหลัง/หลัง' ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="affected_organ3">สันหลัง/หลัง</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="affected_organ" id="affected_organ4" value=" หน้าอก/ไหปลาร้า" <?= $incident['affected_organ'] === 'หน้าอก/ไหปลาร้า' ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="affected_organ4">หน้าอก/ไหปลาร้า</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="affected_organ" id="affected_organ5" value="ช่องท้อง" <?= $incident['affected_organ'] === 'ช่องท้อง' ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="affected_organ5">ช่องท้อง</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="affected_organ" id="affected_organ6" value="เชิงกราน" <?= $incident['affected_organ'] === 'เชิงกราน' ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="affected_organ6">เชิงกราน</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="affected_organ" id="affected_organ7" value="Extremities" <?= $incident['affected_organ'] === 'Extremities' ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="affected_organ7">Extremities</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="affected_organ" id="affected_organ8" value="ผิวหนัง" <?= $incident['affected_organ'] === 'ผิวหนัง' ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="affected_organ8">ผิวหนัง</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="affected_organ" id="affected_organ9" value="Multiple injury back" <?= $incident['affected_organ'] === 'Multiple injury back' ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="affected_organ9">Multiple injury back</label>
                                                            </div>
                                                            <a href="#" class="clear-radio-btn" data-target="affected_organ" style="display: none; margin-left: 15px; margin-top: 7px; padding: 4px 12px; font-size: 12px; color: red; cursor: pointer;">
                                                                ล้างคำตอบ
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- ส่วนที่ 3.2: การช่วยเหลือ -->
                                        <div class="card-body">
                                            <div class="card-title">
                                                <h5>การช่วยเหลือ</h5>
                                            </div>

                                            <!-- ทางเดินหายใจ/การหายใจ -->
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>ทางเดินหายใจ/การหายใจ</label><br>
                                                        <div class="d-flex flex-wrap gap-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="airway_assistance" id="airway_none" value="ไม่" <?= $incident['airway_assistance'] === 'ไม่' ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="airway_none">ไม่</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="airway_assistance" id="airway_open" value="เปิดทางเดินหายใจ" <?= $incident['airway_assistance'] === 'เปิดทางเดินหายใจ' ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="airway_open">เปิดทางเดินหายใจ</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="airway_assistance" id="airway_oral" value="ใส่ Oral airway" <?= $incident['airway_assistance'] === 'ใส่ Oral airway' ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="airway_oral">ใส่ Oral airway</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="airway_assistance" id="airway_oral1" value="ให้ 02 canula/mask" <?= $incident['airway_assistance'] === 'ให้ 02 canula/mask' ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="airway_oral1">ให้ 02 canula/mask</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="airway_assistance" id="airway_oral2" value="Ambu bag" <?= $incident['airway_assistance'] === 'Ambu bag' ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="airway_oral2">Ambu bag</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="airway_assistance" id="airway_oral3" value="Pocket Mask" <?= $incident['airway_assistance'] === 'Pocket Mask' ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="airway_oral3">Pocket Mask</label>
                                                            </div>
                                                            <a href="#" class="clear-radio-btn" data-target="airway_assistance" style="display: none; margin-left: 15px; margin-top: 7px; padding: 4px 12px; font-size: 12px; color: red; cursor: pointer;">
                                                                ล้างคำตอบ
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- บาดแผล/ห้ามเลือด -->
                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>บาดแผล/ห้ามเลือด</label><br>
                                                        <div class="d-flex flex-wrap gap-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="wound_care" id="wound_none" value="ไม่" <?= $incident['wound_care'] === 'ไม่' ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="wound_none">ไม่</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="wound_care" id="wound_pressure" value="การกดห้ามเลือด" <?= $incident['wound_care'] === 'การกดห้ามเลือด' ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="wound_pressure">การกดห้ามเลือด</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="wound_care" id="wound_dressing" value="ทำแผล" <?= $incident['wound_care'] === 'ทำแผล' ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="wound_dressing">ทำแผล</label>
                                                            </div>
                                                            <a href="#" class="clear-radio-btn" data-target="wound_care" style="display: none; margin-left: 15px; margin-top: 7px; padding: 4px 12px; font-size: 12px; color: red; cursor: pointer;">
                                                                ล้างคำตอบ
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- การดามกระดูก -->
                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>การดามกระดูก</label><br>
                                                        <div class="d-flex flex-wrap gap-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="bone_immobilization" id="bone_none" value="ไม่" <?= $incident['bone_immobilization'] === 'ไม่' ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="bone_none">ไม่</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="bone_immobilization" id="bone_splint" value="เฝือกลม/ไม้ดาม/sling" <?= $incident['bone_immobilization'] === 'เฝือกลม/ไม้ดาม/sling' ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="bone_splint">เฝือกลม/ไม้ดาม/sling</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="bone_immobilization" id="bone_none2" value="เฝือกดามคอและกระดานรองหลังยาว" <?= $incident['bone_immobilization'] === 'เฝือกดามคอและกระดานรองหลังยาว' ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="bone_none2">เฝือกดามคอและกระดานรองหลังยาว</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="bone_immobilization" id="bone_none3" value="เฝือกหลังและคอ (KED)" <?= $incident['bone_immobilization'] === 'เฝือกหลังและคอ (KED)' ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="bone_none3">เฝือกหลังและคอ (KED)</label>
                                                            </div>
                                                            <a href="#" class="clear-radio-btn" data-target="bone_immobilization" style="display: none; margin-left: 15px; margin-top: 7px; padding: 4px 12px; font-size: 12px; color: red; cursor: pointer;">
                                                                ล้างคำตอบ
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- ช่วยฟื้นคืนชีพ -->
                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>ช่วยฟื้นคืนชีพ</label><br>
                                                        <div class="d-flex flex-wrap gap-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="resuscitation" id="resuscitation1" value="ไม่ได้ทำ" <?= $incident['resuscitation'] === 'ไม่ได้ทำ' ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="resuscitation1">ไม่ได้ทำ</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="resuscitation" id="resuscitation2" value="ทำ" <?= $incident['resuscitation'] === 'ทำ' ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="resuscitation2">ทำ</label>
                                                            </div>
                                                            <a href="#" class="clear-radio-btn" data-target="resuscitation" style="display: none; margin-left: 15px; margin-top: 7px; padding: 4px 12px; font-size: 12px; color: red; cursor: pointer;">
                                                                ล้างคำตอบ
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- ผลการดูแลรักษาขั้นต้น -->
                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>ผลการดูแลรักษาขั้นต้น</label><br>
                                                        <div class="d-flex flex-wrap gap-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="treatment_result" id="result_refuse" value="ไม่ยอมให้รักษา" <?= $incident['treatment_result'] === 'ไม่ยอมให้รักษา' ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="result_refuse">ไม่ยอมให้รักษา</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="treatment_result" id="result_improved" value="ทุเลา" <?= $incident['treatment_result'] === 'ทุเลา' ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="result_improved">ทุเลา</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="treatment_result" id="result_stable" value="คงเดิม/คงที่" <?= $incident['treatment_result'] === 'คงเดิม/คงที่' ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="result_stable">คงเดิม/คงที่</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="treatment_result" id="result_stable2" value="ทรุดหนัก" <?= $incident['treatment_result'] === 'ทรุดหนัก' ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="result_stable2">ทรุดหนัก</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="treatment_result" id="result_stable3" value="เสียชีวิต ณ จุดเกิดเหตุ" <?= $incident['treatment_result'] === 'เสียชีวิต ณ จุดเกิดเหตุ' ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="result_stable3">เสียชีวิต ณ จุดเกิดเหตุ</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="treatment_result" id="result_stable4" value="เสียชีวิตขณะนำส่ง" <?= $incident['treatment_result'] === 'เสียชีวิตขณะนำส่ง' ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="result_stable4">เสียชีวิตขณะนำส่ง</label>
                                                            </div>
                                                            <a href="#" class="clear-radio-btn" data-target="treatment_result" style="display: none; margin-left: 15px; margin-top: 7px; padding: 4px 12px; font-size: 12px; color: red; cursor: pointer;">
                                                                ล้างคำตอบ
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <!-- ส่วนที่ 4: เกณฑ์การตัดสินใจส่งโรงบาล -->
                                        <div class="card-body">
                                            <div class="card-title">
                                                <h5>4. เกณฑ์การตัดสินใจส่งโรงบาล</h5>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="hospital_destination">นำส่งห้องฉุกเฉินโรงพยาบาล</label>
                                                        <input type="text" class="form-control" id="hospital_destination" name="hospital_destination" value="<?= htmlspecialchars($incident['hospital_destination']) ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>ประเภทโรงพยาบาล</label><br>
                                                        <div class="d-flex align-items-center">
                                                            <div class="form-check me-3">
                                                                <input class="form-check-input" type="radio" name="hospital_type" id="hospital_government" value="รพ.รัฐ" <?= $incident['hospital_type'] === 'รพ.รัฐ' ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="hospital_government">รพ.รัฐ</label>
                                                            </div>
                                                            <div class="form-check me-3">
                                                                <input class="form-check-input" type="radio" name="hospital_type" id="hospital_private" value="รพ.เอกชน" <?= $incident['hospital_type'] === 'รพ.เอกชน' ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="hospital_private">รพ.เอกชน</label>
                                                            </div>
                                                            <a href="#" class="clear-radio-btn" data-target="hospital_type" style="display: none; margin-left: 15px; padding: 4px 12px; font-size: 12px; color: red; cursor: pointer;">
                                                                ล้างคำตอบ
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>เหตุผล (เลือกได้มากกว่า 1 ข้อ)</label><br>
                                                        <div class="d-flex flex-wrap">
                                                            <?php $selectedReasons = explode(', ', $incident['reasons']); ?>
                                                            <div class="form-check me-3 mb-2">
                                                                <input class="form-check-input" type="checkbox" name="reasons[]" id="reason_suitable" value="เหมาะสม/สามารถรักษาได้" <?= in_array('เหมาะสม/สามารถรักษาได้', $selectedReasons) ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="reason_suitable">เหมาะสม/สามารถรักษาได้</label>
                                                            </div>
                                                            <div class="form-check me-3 mb-2">
                                                                <input class="form-check-input" type="checkbox" name="reasons[]" id="reason_nearby" value="อยู่ใกล้" <?= in_array('อยู่ใกล้', $selectedReasons) ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="reason_nearby">อยู่ใกล้</label>
                                                            </div>
                                                            <div class="form-check me-3 mb-2">
                                                                <input class="form-check-input" type="checkbox" name="reasons[]" id="reason_insurance" value="มีหลักประกัน" <?= in_array('มีหลักประกัน', $selectedReasons) ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="reason_insurance">มีหลักประกัน</label>
                                                            </div>
                                                            <div class="form-check me-3 mb-2">
                                                                <input class="form-check-input" type="checkbox" name="reasons[]" id="reason_old_patient" value="เป็นผู้ป่วยเก่า" <?= in_array('เป็นผู้ป่วยเก่า', $selectedReasons) ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="reason_old_patient">เป็นผู้ป่วยเก่า</label>
                                                            </div>
                                                            <div class="form-check me-3 mb-2">
                                                                <input class="form-check-input" type="checkbox" name="reasons[]" id="reason_preference" value="เป็นความประสงค์" <?= in_array('เป็นความประสงค์', $selectedReasons) ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="reason_preference">เป็นความประสงค์</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="report_summarizer">ผู้สรุปรายงาน</label>
                                                        <select class="form-control" name="report_summarizer">
                                                            <?php foreach ($staffList as $staff): ?>
                                                                <option value="<?= $staff ?>" <?= $incident['report_summarizer'] === $staff ? 'selected' : '' ?>><?= $staff ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- ส่วนอื่นๆ ของฟอร์มแก้ไข -->
                                            <!-- ... -->

                                        </div>

                                        <div class="card-action">
                                            <button type="submit" name="update" class="btn btn-success">อัปเดต</button>
                                            <a href="../event/" class="btn btn-danger">ย้อนกลับ</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php }
                if (isset($_GET['details'])) {
                    $id = $_GET['details'];
                    $incident = getIncidentById($pdo, $id);
                    if (!$incident) {
                        $_SESSION['error'] = "ไม่พบข้อมูลที่ต้องการดูรายละเอียด";
                        header("Location: ../event/");
                        exit();
                    }
                    ?>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">รายละเอียดพื้นที่ออกเหตุและเวลา</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label>ชื่อหน่วยบริการ</label>
                                                <input type="text" class="form-control" value="<?= htmlspecialchars($incident['unit_name']) ?>" disabled>
                                            </div>
                                            <div class="col-md-3">
                                                <label>วันที่บันทึก</label>
                                                <input type="text" class="form-control" value="<?= date('d/m/Y', strtotime($incident['record_date'])) ?>" disabled>
                                            </div>
                                            <div class="col-md-3">
                                                <label>ปฏิบัติการที่</label>
                                                <input type="text" class="form-control" value="<?= htmlspecialchars($incident['operation_number']) ?>" disabled>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label>เจ้าหน้าที่ผู้ให้บริการ</label>
                                                <input type="text" class="form-control" value="<?= htmlspecialchars($incident['staff_members']) ?>" disabled>
                                            </div>
                                            <div class="col-md-6">
                                                <label>ผลการปฏิบัติงาน</label>
                                                <input type="text" class="form-control" value="<?= htmlspecialchars($incident['operation_result']) ?>" disabled>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <label>สถานที่เกิดเหตุ</label>
                                                <input type="text" class="form-control" value="<?= htmlspecialchars($incident['incident_location']) ?>" disabled>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <label>เหตุการณ์</label>
                                                <textarea class="form-control" rows="2" disabled><?= htmlspecialchars($incident['incident_description']) ?></textarea>
                                            </div>
                                        </div>

                                        <h5 class="mt-4">เวลา</h5>
                                        <div class="table-responsive mb-4">
                                            <table class="table table-bordered text-center">
                                                <thead>
                                                    <tr>
                                                        <th>รับแจ้ง</th>
                                                        <th>สั่งการ</th>
                                                        <th>ออกฐาน</th>
                                                        <th>ถึงที่เกิดเหตุ</th>
                                                        <th>ออกจากที่เกิดเหตุ</th>
                                                        <th>ถึง รพ.</th>
                                                        <th>กลับถึงฐาน</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td><input type="time" class="form-control" value="<?= $incident['receive_time'] ?>" disabled></td>
                                                        <td><input type="time" class="form-control" value="<?= $incident['order_time'] ?>" disabled></td>
                                                        <td><input type="time" class="form-control" value="<?= $incident['exit_base_time'] ?>" disabled></td>
                                                        <td><input type="time" class="form-control" value="<?= $incident['arrive_scene_time'] ?>" disabled></td>
                                                        <td><input type="time" class="form-control" value="<?= $incident['leave_scene_time'] ?>" disabled></td>
                                                        <td><input type="time" class="form-control" value="<?= $incident['arrive_hospital_time'] ?>" disabled></td>
                                                        <td><input type="time" class="form-control" value="<?= $incident['return_base_time'] ?>" disabled></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <label>ผลการดูแลรักษาขั้นต้น</label>
                                                <input type="text" class="form-control" value="<?= htmlspecialchars($incident['treatment_result']) ?>" disabled>
                                            </div>
                                            <div class="col-md-4">
                                                <label>โรงพยาบาลปลายทาง</label>
                                                <input type="text" class="form-control" value="<?= htmlspecialchars($incident['hospital_destination']) ?>" disabled>
                                            </div>
                                            <div class="col-md-4">
                                                <label>ประเภท รพ.</label>
                                                <input type="text" class="form-control" value="<?= htmlspecialchars($incident['hospital_type']) ?>" disabled>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <label>เหตุผลการส่ง (เลือกได้มากกว่า 1 ข้อ)</label>
                                                <textarea class="form-control" disabled><?= htmlspecialchars($incident['reasons']) ?></textarea>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label>ผู้สรุปรายงาน</label>
                                                <input type="text" class="form-control" value="<?= htmlspecialchars($incident['report_summarizer']) ?>" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-action">
                                        <a href="../event/" class="btn btn-secondary">ย้อนกลับ</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                    </div>
                </div>
            </div>
    </div>
<?php
    include_once("../footer.php");
}
?>
<script>
    // Auto-hide alerts after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            setTimeout(function() {
                if (alert && alert.parentNode) {
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = '0';
                    setTimeout(function() {
                        if (alert.parentNode) {
                            alert.parentNode.removeChild(alert);
                        }
                    }, 500);
                }
            }, 5000);
        });
    });
    // ตรวจสอบเลขบัตรประชาชน
    document.getElementById('patient_id_card').addEventListener('blur', function() {
        const idCardError = document.getElementById('idCardError');
        if (this.value.length !== 13 && this.value.length > 0) {
            idCardError.classList.remove('d-none');
        } else {
            idCardError.classList.add('d-none');
        }
    });

    // จัดการ radio buttons และ clear buttons
    document.querySelectorAll('.clear-radio-btn').forEach(btn => {
        const targetName = btn.getAttribute('data-target');
        const radios = document.querySelectorAll(`input[type="radio"][name="${targetName}"]`);

        // ตรวจสอบว่ามี radio ที่ถูกเลือกอยู่แล้วหรือไม่
        let isChecked = false;
        radios.forEach(radio => {
            if (radio.checked) isChecked = true;
        });
        if (isChecked) btn.style.display = 'inline-block';

        radios.forEach(radio => {
            radio.addEventListener('change', function() {
                btn.style.display = 'inline-block';
            });
        });

        btn.addEventListener('click', function(e) {
            e.preventDefault();
            radios.forEach(radio => {
                radio.checked = false;
            });
            btn.style.display = 'none';
        });
    });

    // ตรวจสอบฟอร์มก่อนส่ง
    document.addEventListener('DOMContentLoaded', function() {
        const forms = document.querySelectorAll('form[role="form"]');
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                let isValid = true;

                // ตรวจสอบฟิลด์ที่จำเป็น
                const requiredFields = form.querySelectorAll('[required]');
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        isValid = false;
                        field.classList.add('is-invalid');
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });

                // ตรวจสอบ radio buttons ที่จำเป็น
                const requiredRadios = form.querySelectorAll('input[type="radio"][required]');
                requiredRadios.forEach(radio => {
                    const groupName = radio.name;
                    const checked = form.querySelector(`input[type="radio"][name="${groupName}"]:checked`);
                    if (!checked) {
                        isValid = false;
                        const firstRadio = form.querySelector(`input[type="radio"][name="${groupName}"]`);
                        firstRadio.classList.add('is-invalid');
                    } else {
                        const radios = form.querySelectorAll(`input[type="radio"][name="${groupName}"]`);
                        radios.forEach(r => r.classList.remove('is-invalid'));
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    alert('กรุณากรอกข้อมูลในฟิลด์ที่จำเป็นให้ครบถ้วน');
                }
            });
        });
    });
    // แสดงจำนวนที่เลือกในปุ่ม dropdown
    document.addEventListener("DOMContentLoaded", function() {
        const dropdownBtn = document.getElementById('dropdownStaff');
        const checkboxes = document.querySelectorAll('input[name="staff_members[]"]');

        function updateDropdownLabel() {
            const checked = Array.from(checkboxes)
                .filter(cb => cb.checked)
                .map(cb => cb.value);
            dropdownBtn.textContent = checked.length ? checked.join(', ') : 'เลือกเจ้าหน้าที่';
        }

        checkboxes.forEach(cb => cb.addEventListener('change', updateDropdownLabel));
        updateDropdownLabel(); // เรียกใช้ตอนโหลดหน้า
    });
</script>