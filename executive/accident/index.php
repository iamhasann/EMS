<?php
session_start();
$currentDir = basename(dirname($_SERVER['PHP_SELF'])); // Get current directory name
include_once("../sidebar.php");
?>
<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">งานอุบัติเหตุฉุกเฉิน</h3>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card p-3">
                <h5 class="mb-3 text-primary">
                    <i class="fas fa-filter"></i><b> ตัวกรองงานอุบัติเหตุฉุกเฉิน</b>
                </h5>
                <form>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">ค้นหา</label>
                                <input type="text" class="form-control" placeholder="พิมพ์ชื่องานอุบัติเหตุฉุกเฉิน/รหัสงาน/ชื่อผู้บันทึก" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="name_en" class="form-label">ตั้งแต่วันที่</label>
                                <input type="date" class="form-control" placeholder="ตั้งแต่วันที่" />
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="name_en" class="form-label">ถึงวันที่</label>
                                <input type="date" class="form-control" placeholder="ถึงวันที่" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 d-flex justify-content-end align-items-end">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> ค้นหา
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6 col-md-6">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div
                                class="icon-big text-center icon-primary bubble-shadow-small">
                                <i class="fas fa-briefcase-medical"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">งานอุบัติเหตุฉุกเฉินทั้งหมด (ครั้ง)</p>
                                <h4 class="card-title">453</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-6">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div
                                class="icon-big text-center icon-success bubble-shadow-small">
                                <i class="fas fa-chart-line"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">ประเภทงานอุบัติเหตุฉุกเฉินมากที่สุด (ประเภท)</p>
                                <h4 class="card-title">19 (เย็บแผล)</h4>
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
                    <h4 class="card-title">งานอุบัติเหตุฉุกเฉิน</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table
                            id="basic-datatables"
                            class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ประทับเวลา (วันที่)</th>
                                    <th>ฉีดยา</th>
                                    <th>ดูดเสมหะ</th>
                                    <th>ตัดไหม</th>
                                    <th>ทำแผล</th>
                                    <th>พ่นยา</th>
                                    <th>เย็บแผล</th>
                                    <th>ให้น้ำเกลือ</th>
                                    <th>เครื่องมือ</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>ประทับเวลา (วันที่)</th>
                                    <th>ฉีดยา</th>
                                    <th>ดูดเสมหะ</th>
                                    <th>ตัดไหม</th>
                                    <th>ทำแผล</th>
                                    <th>พ่นยา</th>
                                    <th>เย็บแผล</th>
                                    <th>ให้น้ำเกลือ</th>
                                    <th>เครื่องมือ</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                <tr>
                                    <td>24 พ.ค. 2568</td>
                                    <td>5</td>
                                    <td>10</td>
                                    <td>9</td>
                                    <td>6</td>
                                    <td>2</td>
                                    <td>4</td>
                                    <td>1</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-info me-1" title="ดูรายละเอียด">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning me-1" title="แก้ไข">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger me-1" title="ลบ">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>25 พ.ค. 2568</td>
                                    <td>7</td>
                                    <td>1</td>
                                    <td>8</td>
                                    <td>3</td>
                                    <td>5</td>
                                    <td>6</td>
                                    <td>10</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-info me-1" title="ดูรายละเอียด">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning me-1" title="แก้ไข">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger me-1" title="ลบ">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>26 พ.ค. 2568</td>
                                    <td>1</td>
                                    <td>6</td>
                                    <td>7</td>
                                    <td>2</td>
                                    <td>8</td>
                                    <td>9</td>
                                    <td>3</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-info me-1" title="ดูรายละเอียด">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning me-1" title="แก้ไข">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger me-1" title="ลบ">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>27 พ.ค. 2568</td>
                                    <td>10</td>
                                    <td>1</td>
                                    <td>5</td>
                                    <td>4</td>
                                    <td>7</td>
                                    <td>8</td>
                                    <td>9</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-info me-1" title="ดูรายละเอียด">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning me-1" title="แก้ไข">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger me-1" title="ลบ">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>28 พ.ค. 2568</td>
                                    <td>6</td>
                                    <td>1</td>
                                    <td>8</td>
                                    <td>9</td>
                                    <td>7</td>
                                    <td>3</td>
                                    <td>2</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-info me-1" title="ดูรายละเอียด">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning me-1" title="แก้ไข">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger me-1" title="ลบ">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>29 พ.ค. 2568</td>
                                    <td>2</td>
                                    <td>3</td>
                                    <td>4</td>
                                    <td>5</td>
                                    <td>6</td>
                                    <td>7</td>
                                    <td>8</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-info me-1" title="ดูรายละเอียด">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning me-1" title="แก้ไข">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger me-1" title="ลบ">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>30 พ.ค. 2568</td>
                                    <td>9</td>
                                    <td>10</td>
                                    <td>1</td>
                                    <td>2</td>
                                    <td>3</td>
                                    <td>4</td>
                                    <td>5</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-info me-1" title="ดูรายละเอียด">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning me-1" title="แก้ไข">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger me-1" title="ลบ">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>31 พ.ค. 2568</td>
                                    <td>6</td>
                                    <td>7</td>
                                    <td>8</td>
                                    <td>9</td>
                                    <td>10</td>
                                    <td>1</td>
                                    <td>2</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-info me-1" title="ดูรายละเอียด">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning me-1" title="แก้ไข">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger me-1" title="ลบ">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>1 มิ.ย. 2568</td>
                                    <td>3</td>
                                    <td>4</td>
                                    <td>5</td>
                                    <td>6</td>
                                    <td>7</td>
                                    <td>8</td>
                                    <td>9</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-info me-1" title="ดูรายละเอียด">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning me-1" title="แก้ไข">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger me-1" title="ลบ">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2 มิ.ย. 2568</td>
                                    <td>10</td>
                                    <td>1</td>
                                    <td>2</td>
                                    <td>3</td>
                                    <td>4</td>
                                    <td>5</td>
                                    <td>6</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-info me-1" title="ดูรายละเอียด">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning me-1" title="แก้ไข">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger me-1" title="ลบ">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>3 มิ.ย. 2568</td>
                                    <td>7</td>
                                    <td>8</td>
                                    <td>9</td>
                                    <td>10</td>
                                    <td>1</td>
                                    <td>2</td>
                                    <td>3</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-info me-1" title="ดูรายละเอียด">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning me-1" title="แก้ไข">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger me-1" title="ลบ">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>4 มิ.ย. 2568</td>
                                    <td>4</td>
                                    <td>5</td>
                                    <td>6</td>
                                    <td>7</td>
                                    <td>8</td>
                                    <td>9</td>
                                    <td>10</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-info me-1" title="ดูรายละเอียด">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning me-1" title="แก้ไข">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger me-1" title="ลบ">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>5 มิ.ย. 2568</td>
                                    <td>1</td>
                                    <td>2</td>
                                    <td>3</td>
                                    <td>4</td>
                                    <td>5</td>
                                    <td>6</td>
                                    <td>7</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-info me-1" title="ดูรายละเอียด">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning me-1" title="แก้ไข">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger me-1" title="ลบ">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>6 มิ.ย. 2568</td>
                                    <td>8</td>
                                    <td>9</td>
                                    <td>10</td>
                                    <td>1</td>
                                    <td>2</td>
                                    <td>3</td>
                                    <td>4</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-info me-1" title="ดูรายละเอียด">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning me-1" title="แก้ไข">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger me-1" title="ลบ">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>7 มิ.ย. 2568</td>
                                    <td>5</td>
                                    <td>6</td>
                                    <td>7</td>
                                    <td>8</td>
                                    <td>9</td>
                                    <td>10</td>
                                    <td>1</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-info me-1" title="ดูรายละเอียด">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning me-1" title="แก้ไข">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger me-1" title="ลบ">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>8 มิ.ย. 2568</td>
                                    <td>2</td>
                                    <td>3</td>
                                    <td>4</td>
                                    <td>5</td>
                                    <td>6</td>
                                    <td>7</td>
                                    <td>8</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-info me-1" title="ดูรายละเอียด">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning me-1" title="แก้ไข">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger me-1" title="ลบ">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>9 มิ.ย. 2568</td>
                                    <td>9</td>
                                    <td>10</td>
                                    <td>1</td>
                                    <td>2</td>
                                    <td>3</td>
                                    <td>4</td>
                                    <td>5</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-info me-1" title="ดูรายละเอียด">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning me-1" title="แก้ไข">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger me-1" title="ลบ">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>10 มิ.ย. 2568</td>
                                    <td>6</td>
                                    <td>7</td>
                                    <td>8</td>
                                    <td>9</td>
                                    <td>10</td>
                                    <td>1</td>
                                    <td>2</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-info me-1" title="ดูรายละเอียด">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning me-1" title="แก้ไข">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger me-1" title="ลบ">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>11 มิ.ย. 2568</td>
                                    <td>3</td>
                                    <td>4</td>
                                    <td>5</td>
                                    <td>6</td>
                                    <td>7</td>
                                    <td>8</td>
                                    <td>9</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-info me-1" title="ดูรายละเอียด">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning me-1" title="แก้ไข">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger me-1" title="ลบ">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>12 มิ.ย. 2568</td>
                                    <td>10</td>
                                    <td>1</td>
                                    <td>2</td>
                                    <td>3</td>
                                    <td>4</td>
                                    <td>5</td>
                                    <td>6</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-info me-1" title="ดูรายละเอียด">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning me-1" title="แก้ไข">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger me-1" title="ลบ">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>13 มิ.ย. 2568</td>
                                    <td>7</td>
                                    <td>8</td>
                                    <td>9</td>
                                    <td>10</td>
                                    <td>1</td>
                                    <td>2</td>
                                    <td>3</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-info me-1" title="ดูรายละเอียด">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning me-1" title="แก้ไข">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger me-1" title="ลบ">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>14 มิ.ย. 2568</td>
                                    <td>4</td>
                                    <td>5</td>
                                    <td>6</td>
                                    <td>7</td>
                                    <td>8</td>
                                    <td>9</td>
                                    <td>10</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-info me-1" title="ดูรายละเอียด">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning me-1" title="แก้ไข">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger me-1" title="ลบ">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>15 มิ.ย. 2568</td>
                                    <td>1</td>
                                    <td>2</td>
                                    <td>3</td>
                                    <td>4</td>
                                    <td>5</td>
                                    <td>6</td>
                                    <td>7</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-info me-1" title="ดูรายละเอียด">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning me-1" title="แก้ไข">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger me-1" title="ลบ">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>16 มิ.ย. 2568</td>
                                    <td>8</td>
                                    <td>9</td>
                                    <td>10</td>
                                    <td>1</td>
                                    <td>2</td>
                                    <td>3</td>
                                    <td>4</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-info me-1" title="ดูรายละเอียด">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning me-1" title="แก้ไข">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger me-1" title="ลบ">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>17 มิ.ย. 2568</td>
                                    <td>5</td>
                                    <td>6</td>
                                    <td>7</td>
                                    <td>8</td>
                                    <td>9</td>
                                    <td>10</td>
                                    <td>1</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-info me-1" title="ดูรายละเอียด">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning me-1" title="แก้ไข">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger me-1" title="ลบ">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>18 มิ.ย. 2568</td>
                                    <td>2</td>
                                    <td>3</td>
                                    <td>4</td>
                                    <td>5</td>
                                    <td>6</td>
                                    <td>7</td>
                                    <td>8</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-info me-1" title="ดูรายละเอียด">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning me-1" title="แก้ไข">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger me-1" title="ลบ">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>19 มิ.ย. 2568</td>
                                    <td>9</td>
                                    <td>10</td>
                                    <td>1</td>
                                    <td>2</td>
                                    <td>3</td>
                                    <td>4</td>
                                    <td>5</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-info me-1" title="ดูรายละเอียด">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning me-1" title="แก้ไข">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger me-1" title="ลบ">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>20 มิ.ย. 2568</td>
                                    <td>6</td>
                                    <td>7</td>
                                    <td>8</td>
                                    <td>9</td>
                                    <td>10</td>
                                    <td>1</td>
                                    <td>2</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-info me-1" title="ดูรายละเอียด">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning me-1" title="แก้ไข">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger me-1" title="ลบ">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>21 มิ.ย. 2568</td>
                                    <td>3</td>
                                    <td>4</td>
                                    <td>5</td>
                                    <td>6</td>
                                    <td>7</td>
                                    <td>8</td>
                                    <td>9</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-info me-1" title="ดูรายละเอียด">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning me-1" title="แก้ไข">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger me-1" title="ลบ">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>22 มิ.ย. 2568</td>
                                    <td>10</td>
                                    <td>1</td>
                                    <td>2</td>
                                    <td>3</td>
                                    <td>4</td>
                                    <td>5</td>
                                    <td>6</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-info me-1" title="ดูรายละเอียด">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning me-1" title="แก้ไข">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger me-1" title="ลบ">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>23 มิ.ย. 2568</td>
                                    <td>7</td>
                                    <td>8</td>
                                    <td>9</td>
                                    <td>10</td>
                                    <td>1</td>
                                    <td>2</td>
                                    <td>3</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-info me-1" title="ดูรายละเอียด">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning me-1" title="แก้ไข">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger me-1" title="ลบ">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>24 มิ.ย. 2568</td>
                                    <td>4</td>
                                    <td>5</td>
                                    <td>6</td>
                                    <td>7</td>
                                    <td>8</td>
                                    <td>9</td>
                                    <td>10</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-info me-1" title="ดูรายละเอียด">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning me-1" title="แก้ไข">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger me-1" title="ลบ">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>25 มิ.ย. 2568</td>
                                    <td>1</td>
                                    <td>2</td>
                                    <td>3</td>
                                    <td>4</td>
                                    <td>5</td>
                                    <td>6</td>
                                    <td>7</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-info me-1" title="ดูรายละเอียด">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning me-1" title="แก้ไข">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger me-1" title="ลบ">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>26 มิ.ย. 2568</td>
                                    <td>8</td>
                                    <td>9</td>
                                    <td>10</td>
                                    <td>1</td>
                                    <td>2</td>
                                    <td>3</td>
                                    <td>4</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-info me-1" title="ดูรายละเอียด">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning me-1" title="แก้ไข">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger me-1" title="ลบ">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>27 มิ.ย. 2568</td>
                                    <td>5</td>
                                    <td>6</td>
                                    <td>7</td>
                                    <td>8</td>
                                    <td>9</td>
                                    <td>10</td>
                                    <td>1</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-info me-1" title="ดูรายละเอียด">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning me-1" title="แก้ไข">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger me-1" title="ลบ">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>28 มิ.ย. 2568</td>
                                    <td>2</td>
                                    <td>3</td>
                                    <td>4</td>
                                    <td>5</td>
                                    <td>6</td>
                                    <td>7</td>
                                    <td>8</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-info me-1" title="ดูรายละเอียด">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning me-1" title="แก้ไข">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger me-1" title="ลบ">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>29 มิ.ย. 2568</td>
                                    <td>9</td>
                                    <td>10</td>
                                    <td>1</td>
                                    <td>2</td>
                                    <td>3</td>
                                    <td>4</td>
                                    <td>5</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-info me-1" title="ดูรายละเอียด">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning me-1" title="แก้ไข">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger me-1" title="ลบ">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>30 มิ.ย. 2568</td>
                                    <td>6</td>
                                    <td>7</td>
                                    <td>8</td>
                                    <td>9</td>
                                    <td>10</td>
                                    <td>1</td>
                                    <td>2</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-info me-1" title="ดูรายละเอียด">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning me-1" title="แก้ไข">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger me-1" title="ลบ">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>1 ก.ค. 2568</td>
                                    <td>3</td>
                                    <td>4</td>
                                    <td>5</td>
                                    <td>6</td>
                                    <td>7</td>
                                    <td>8</td>
                                    <td>9</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-info me-1" title="ดูรายละเอียด">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning me-1" title="แก้ไข">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger me-1" title="ลบ">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2 ก.ค. 2568</td>
                                    <td>10</td>
                                    <td>1</td>
                                    <td>2</td>
                                    <td>3</td>
                                    <td>4</td>
                                    <td>5</td>
                                    <td>6</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-info me-1" title="ดูรายละเอียด">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning me-1" title="แก้ไข">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger me-1" title="ลบ">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>3 ก.ค. 2568</td>
                                    <td>7</td>
                                    <td>8</td>
                                    <td>9</td>
                                    <td>10</td>
                                    <td>1</td>
                                    <td>2</td>
                                    <td>3</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-info me-1" title="ดูรายละเอียด">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning me-1" title="แก้ไข">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger me-1" title="ลบ">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>4 ก.ค. 2568</td>
                                    <td>4</td>
                                    <td>5</td>
                                    <td>6</td>
                                    <td>7</td>
                                    <td>8</td>
                                    <td>9</td>
                                    <td>10</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-info me-1" title="ดูรายละเอียด">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning me-1" title="แก้ไข">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger me-1" title="ลบ">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>5 ก.ค. 2568</td>
                                    <td>1</td>
                                    <td>2</td>
                                    <td>3</td>
                                    <td>4</td>
                                    <td>5</td>
                                    <td>6</td>
                                    <td>7</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-info me-1" title="ดูรายละเอียด">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning me-1" title="แก้ไข">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger me-1" title="ลบ">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>6 ก.ค. 2568</td>
                                    <td>8</td>
                                    <td>9</td>
                                    <td>10</td>
                                    <td>1</td>
                                    <td>2</td>
                                    <td>3</td>
                                    <td>4</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-info me-1" title="ดูรายละเอียด">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning me-1" title="แก้ไข">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger me-1" title="ลบ">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>7 ก.ค. 2568</td>
                                    <td>5</td>
                                    <td>6</td>
                                    <td>7</td>
                                    <td>8</td>
                                    <td>9</td>
                                    <td>10</td>
                                    <td>1</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-info me-1" title="ดูรายละเอียด">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning me-1" title="แก้ไข">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger me-1" title="ลบ">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>8 ก.ค. 2568</td>
                                    <td>2</td>
                                    <td>3</td>
                                    <td>4</td>
                                    <td>5</td>
                                    <td>6</td>
                                    <td>7</td>
                                    <td>8</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-info me-1" title="ดูรายละเอียด">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning me-1" title="แก้ไข">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger me-1" title="ลบ">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include_once("../footer.php");
?>