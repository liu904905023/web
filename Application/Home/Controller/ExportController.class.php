<?php
namespace Home\Controller;

use Common\Compose\Base;

class ExportController extends Base {
    //导出交易订单
    public function exportorder() {
        $storename = $_POST['storename'];//商户名称
        $Time_Start = empty($_POST['dtp_input1']) ? $_POST['dtp_input1'] : $_POST['dtp_input1'] . " 00:00:00";//开始时间
        $Time_end = empty($_POST['dtp_input2']) ? $_POST['dtp_input2'] : $_POST['dtp_input2'] . " 23:59:59";//截止时间
        $out_trade_no = $_POST['ordernum'];//订单号
        $Customer = $_POST['Customer'];//跳转页商户名称
        $SystemUserSysNo = $_POST['SystemUserSysNo'];//员工主键
        $CustomerNames = $_POST['CustomerNames'];
        $ButtonType = I('input_hidden', "");
        $SysNo = I('SysNo', "");
        $TopSysNo = I('TopSysNo', "");
        $Top = I('Top', "");
        $CustomerSysNo = I('CustomerSysNo', "");
        $Ordertype = $_POST['Ordertype'];//全部 微信 支付宝
        $ButtonType = I('input_hidden', "");//查询 汇总

        $data = array("Time_Start" => $Time_Start, "Time_end" => $Time_end, "Out_trade_no" => $out_trade_no, "CustomerName" => $CustomerNames, "Customer" => $storename, "Pay_Type" => $Ordertype);

        $flag = session('flag');//服务商商户0 或员工1
        $type = session('servicestoretype');//员工的服务商的类型 0为服务  1为商户

        if (session('data')['CustomersType'] == 0 & $type == 0) {
            $stafftype = 0;
        }
        if (session('data')['CustomersType'] == 0 || $stafftype == 0) {//服务商或者服务商员工登陆 必须填写商户名进行查询，不需要传递CustomerSysNo
            if ($Customer == 'NaN') {
                $data['Customer'] = '';
            } else {
                $data['Customer'] = $Customer;
            }
            if (($storename == '') && ($Customer != 'NaN')) {
                $data['Customer'] = $Customer;
            } else {
                $data['Customer'] = $storename;
            }
        }

        if (($ButtonType == "a") || ($ButtonType == "")) {//查询
            if (session('data')['CustomersType'] == 0 & $flag == 0) {//服务商登陆
                if ($SysNo != 'NaN') {
                    $url = C('SERVER_HOST') . "IPP3Order/IPP3OrderListShopSPRate";
                    $data['CustomerSysNo'] = $SysNo;
                }
                if ($TopSysNo != 'NaN') {
                    $url = C('SERVER_HOST') . "IPP3Order/IPP3OrderListCustomerUserRateSP";
                    $data['SystemUserTopSysNo'] = $TopSysNo;
                    $data['CustomersTopSysNo'] = session('SysNO');
                }
                if ($CustomerSysNo != 'NaN') {
                    $url = C('SERVER_HOST') . "IPP3Order/IPP3OrderListCustomerUserRateSP";
                    $data['SystemUserTopSysNo'] = $CustomerSysNo;
                    $data['CustomersTopSysNo'] = session('SysNO');
                }
            } else if (session('data')['CustomersType'] == 1 & $flag == 0) {//商户
                if ($TopSysNo != 'NaN') {
                    $url = C('SERVER_HOST') . "IPP3Order/IPP3OrderListShopUserRateSP";
                    $data['SystemUserSysNo'] = $TopSysNo;
                } else {
                    $url = C('SERVER_HOST') . "IPP3Order/IPP3OrderListShopSPRate";
                    $data['CustomerSysNo'] = session('SysNO');
                }
            } else if ($type == 0 & $flag == 1) {//服务商员工
                if ($SysNo != 'NaN') {
                    $url = C('SERVER_HOST') . "IPP3Order/IPP3OrderListShopSPRate";
                    $data['CustomerSysNo'] = $SysNo;
                } else {
                    $url = C('SERVER_HOST') . "IPP3Order/IPP3OrderListCustomerUserRateSP";
                    $data['SystemUserTopSysNo'] = session('SysNO');
                    $data['CustomersTopSysNo'] = session('servicestoreno');
                }
            } else if ($type == 1 & $flag == 1) {//商户员工
                $url = C('SERVER_HOST') . "IPP3Order/IPP3OrderListShopUserRateSP";
                $data['SystemUserSysNo'] = session('SysNO');
            }
        } else if ($ButtonType == "b") {//汇总
            if (session('data')['CustomersType'] == 0 & $flag == 0) {//服务商登陆
                if ($SysNo != 'NaN') {
                    $url = C('SERVER_HOST') . "IPP3Order/IPP3Order_Fund_ShopSPRate";
                    $data['CustomerSysNo'] = $SysNo;
                }
                if ($TopSysNo != 'NaN') {
                    $url = C('SERVER_HOST') . "IPP3Order/IPP3Order_Fund_CustomerUserRateSP";
                    $data['SystemUserTopSysNo'] = $TopSysNo;
                    $data['CustomersTopSysNo'] = session('SysNO');
                }
                if ($CustomerSysNo != 'NaN') {
                    $url = C('SERVER_HOST') . "IPP3Order/IPP3Order_Fund_CustomerUserRateSP";
                    $data['SystemUserTopSysNo'] = $CustomerSysNo;
                    $data['CustomersTopSysNo'] = session('SysNO');
                }
            } else if (session('data')['CustomersType'] == 1 & $flag == 0) {//商户
                if ($TopSysNo != 'NaN') {
                    $url = C('SERVER_HOST') . "IPP3Order/IPP3Order_Fund_ShopUserRateSP";
                    $data['SystemUserSysNo'] = $TopSysNo;
                } else {
                    $url = C('SERVER_HOST') . "IPP3Order/IPP3Order_Fund_ShopSPRate";
                    $data['CustomerSysNo'] = session('SysNO');
                }
            } else if ($type == 0 & $flag == 1) {//服务商员工
                if ($SysNo != 'NaN') {
                    $url = C('SERVER_HOST') . "IPP3Order/IPP3Order_Fund_ShopSPRate";
                    $data['CustomerSysNo'] = $SysNo;
                } else {
                    $url = C('SERVER_HOST') . "IPP3Order/IPP3Order_Fund_CustomerUserRateSP";
                    $data['SystemUserTopSysNo'] = session('SysNO');
                    $data['CustomersTopSysNo'] = session('servicestoreno');
                }
            } else if ($type == 1 & $flag == 1) {//商户员工
                if ($SysNo != 'NaN') {
                    $url = C('SERVER_HOST') . "IPP3Order/IPP3Order_Fund_ShopUserRateSP";
                    $data['SystemUserSysNo'] = $SysNo;
                } else {
                    $url = C('SERVER_HOST') . "IPP3Order/IPP3Order_Fund_ShopUserRateSP";
                    $data['SystemUserSysNo'] = session('SysNO');
                }
            }
        }

        $data['PagingInfo']['PageSize'] = 100000;
        $data['PagingInfo']['PageNumber'] = 0;
        $data = json_encode($data);

        $head = array("Content-Type:application/json;charset=UTF-8", "Content-length:" . strlen($data), "X-Ywkj-Authentication:" . strlen($data));

        $list = http_request($url, $data, $head);
        $list = json_decode($list, true);

        if (($ButtonType == "a") || ($ButtonType == "")) {
            foreach ($list['model'] as $row => $val) {
                $info[$row]['SysNo'] = $val['SysNo'];
                $info[$row]['CustomerName'] = $val['CustomerName'];
                if (session('data')['CustomersType'] == 1) {
                    $info[$row]['LoginName'] = $val['LoginName'];
                    $info[$row]['DisplayName'] = $val['DisplayName'];
                }
                $info[$row]['Out_trade_no'] = "'" . $val['Out_trade_no'];
                $info[$row]['Total_fee'] = fee2yuan($val['Total_fee']);

                if ($TopSysNo != 'NaN') {
                    if (session('data')['CustomersType'] == 0 & $flag == 0) {
                        $info[$row]['UserRate'] = $val['UserRate'];
                        $info[$row]['UserRate_fee'] = fee2yuan($val['UserRate_fee']);
                    } else if ($type == 1 & $flag == 1) {
                        $info[$row]['Rate'] = $val['Rate'];
                        // $info[$row]['Rate_fee']=fee2yuan($val['Rate_fee']);
                    } else if (session('data')['CustomersType'] == 1 & $flag == 0) {
                        $info[$row]['Rate'] = $val['Rate'];
                        // $info[$row]['Rate_fee']=fee2yuan($val['Rate_fee']);
                    }
                } else if ($_POST['Customer'] == 'NaN' & $TopSysNo == 'NaN' & $Top == 'NaN') {
                    if ($type == 1 & $flag == 1) {
                        $info[$row]['Rate'] = $val['Rate'];
                    } else if (session('data')['CustomersType'] == 1 & $flag == 0) {
                        $info[$row]['Rate'] = $val['Rate'];
                    } else {
                        $info[$row]['Rate'] = $val['Rate'];
                        $info[$row]['Rate_fee'] = fee2yuan($val['Rate_fee']);
                    }
                } else if ($SysNo != 'NaN') {
                    if ($type == 1 & $flag == 1) {
                        $info[$row]['Rate'] = $val['Rate'];
                    } else if (session('data')['CustomersType'] == 1 & $flag == 0) {
                        $info[$row]['Rate'] = $val['Rate'];
                    } else {
                        $info[$row]['Rate'] = $val['Rate'];
                        $info[$row]['Rate_fee'] = fee2yuan($val['Rate_fee']);
                    }
                } else if ($Top != 'NaN') {
                    if ($type == 1 & $flag == 1) {
                        $info[$row]['UserRate'] = $val['UserRate'];
                    } else if (session('data')['CustomersType'] == 1 & $flag == 0) {
                        $info[$row]['UserRate'] = $val['UserRate'];
                    } else {
                        $info[$row]['UserRate'] = $val['UserRate'];
                        $info[$row]['UserRate_fee'] = fee2yuan($val['UserRate_fee']);
                    }
                } else if ($Customer != 'NaN') {
                    if ($type == 1 & $flag == 1) {
                        $info[$row]['UserRate'] = $val['UserRate'];
                    } else if (session('data')['CustomersType'] == 1 & $flag == 0) {
                        $info[$row]['UserRate'] = $val['UserRate'];
                    } else {
                        $info[$row]['UserRate'] = $val['UserRate'];
                        $info[$row]['UserRate_fee'] = fee2yuan($val['UserRate_fee']);
                    }
                } else if ($CustomerSysNo != 'NaN') {
                    if ($type == 1 & $flag == 1) {
                        $info[$row]['UserRate'] = $val['UserRate'];
                    } else if (session('data')['CustomersType'] == 1 & $flag == 0) {
                        $info[$row]['UserRate'] = $val['UserRate'];
                    } else {
                        $info[$row]['UserRate'] = $val['UserRate'];
                        $info[$row]['UserRate_fee'] = fee2yuan($val['UserRate_fee']);
                    }
                } else {
                    if ($type == 1 & $flag == 1) {
                        $info[$row]['Rate'] = $val['Rate'];
                    } else if (session('data')['CustomersType'] == 1 & $flag == 0) {
                        $info[$row]['Rate'] = $val['Rate'];
                    } else {
                        $info[$row]['Rate'] = $val['Rate'];
                        $info[$row]['Rate_fee'] = fee2yuan($val['Rate_fee']);
                    }
                }
                $info[$row]['Pay_Type'] = $val['Pay_Type'];
                if ($val['Pay_Type'] == 102) {
                    $info[$row]['Pay_Type'] = "微信";
                } else if ($val['Pay_Type'] == 103) {
                    $info[$row]['Pay_Type'] = "支付宝";
                } else if ($val['Pay_Type'] == 104) {
                    $info[$row]['Pay_Type'] = "威富通(微信)";
                }
                $info[$row]['Cash_fee_type'] = "人民币";
                $info[$row]['Time_Start'] = $val['Time_Start'];
            }

            foreach ($info as $field => $v) {
                if ($field == 'SysNo') {
                    $headArr[] = '序号';
                }
                if ($field == 'CustomerName') {
                    $headArr[] = '商户名称';
                }
                if (session('data')['CustomersType'] == 1) {
                    if ($field == 'LoginName') {
                        $headArr[] = '登录名';
                    }
                    if ($field == 'DisplayName') {
                        $headArr[] = '真实姓名';
                    }
                }
                if ($field == 'Out_trade_no') {
                    $headArr[] = '订单号';
                }
                if ($field == 'Total_fee') {
                    $headArr[] = '交易金额';
                }
                if ($type == 1 & $flag == 1) {
                    if ($field == 'Rate') {
                        $headArr[] = '员工费率';
                    }
                    // if($field == 'Rate_fee'){
                    //     $headArr[]='返佣';
                    // }
                } else if ($type == 0 & $flag == 1) {
                    if ($SysNo != 'NaN') {
                        if ($field == 'UserRate') {
                            $headArr[] = '商户费率';
                        }
                        if ($field == 'UserRate_fee') {
                            $headArr[] = '返佣';
                        }
                    } else if ($Customer != 'NaN') {

                        if ($field == 'Rate') {
                            $headArr[] = '员工费率';
                        }
                        if ($field == 'Rate_fee') {
                            $headArr[] = '返佣';
                        }
                    } else if ($Top != 'NaN') {
                        if ($field == 'Rate') {
                            $headArr[] = '员工费率';
                        }
                        if ($field == 'Rate_fee') {
                            $headArr[] = '返佣';
                        }
                    } else {
                        if ($field == 'UserRate') {
                            $headArr[] = '商户费率';
                        }
                        if ($field == 'UserRate_fee') {
                            $headArr[] = '返佣';
                        }
                    }
                } else if (session('data')['CustomersType'] == 1 & $flag == 0) {
                    if ($TopSysNo != 'NaN') {

                        if ($field == 'Rate') {
                            $headArr[] = '员工费率';
                        }
                        // if($field == 'Rate_fee'){
                        //     $headArr[]='返佣';
                        // }
                    } else {
                        if ($field == 'UserRate') {
                            $headArr[] = '商户费率';
                        }
                        // if($field == 'UserRate_fee'){
                        //     $headArr[]='返佣';
                        // }
                    }
                } else if (session('data')['CustomersType'] == 0 & $flag == 0) {
                    if ($SysNo != 'NaN') {
                        if ($field == 'UserRate') {
                            $headArr[] = '商户费率';
                        }
                        if ($field == 'UserRate_fee') {
                            $headArr[] = '返佣';
                        }
                    } else if ($CustomerSysNo != 'NaN') {
                        if ($field == 'UserRate') {
                            $headArr[] = '商户费率';
                        }
                        if ($field == 'UserRate_fee') {
                            $headArr[] = '返佣';
                        }
                    } else {
                        if ($field == 'Rate') {
                            $headArr[] = '上级费率';
                        }
                        if ($field == 'Rate_fee') {
                            $headArr[] = '返佣';
                        }
                    }
                }
                if ($field == 'Pay_Type') {
                    $headArr[] = '交易类型';
                }
                if ($field == 'Cash_fee_type') {
                    $headArr[] = '交易币种';
                }
                if ($field == 'Time_Start') {
                    $headArr[] = '交易时间';
                }
            }
            $filename = "交易费率订单查询报表";

        } else if ($ButtonType == "b") {

            foreach ($list['model'] as $row => $val) {
                // $info[$row]['DisplayName']=$val['DisplayName'];
                // $info[$row]['CustomerName']=$val['CustomerName'];
                if (session('data')['CustomersType'] == 0 & $flag == 0) {//服务商登陆
                    if ($SysNo != 'NaN') {
                        $info[$row]['DisplayName'] = $val['DisplayName'];
                    } else if ($CustomerSysNo != 'NaN') {
                        $info[$row]['CustomerName'] = $val['CustomerName'];
                    } else if ($TopSysNo != 'NaN') {
                        $info[$row]['CustomerName'] = $val['CustomerName'];
                    }
                }
                if (session('data')['CustomersType'] == 1 & $flag == 0) {//商户登陆

                    $info[$row]['DisplayName'] = $val['DisplayName'];
                }
                if ($type == 0 & $flag == 1) {//服务商员工
                    if ($Customer != 'NaN') {
                        $info[$row]['CustomerName'] = $val['CustomerName'];
                    } else if ($SysNo != 'NaN') {
                        $info[$row]['DisplayName'] = $val['DisplayName'];
                    } else {
                        $info[$row]['CustomerName'] = $val['CustomerName'];
                    }
                }
                if ($type == 1 & $flag == 1) {//商户员工

                    $info[$row]['DisplayName'] = $val['DisplayName'];
                }

                $info[$row]['Cash_fee'] = fee2yuan($val['Cash_fee']);
                $info[$row]['fee'] = fee2yuan($val['fee']);

                if ($TopSysNo != 'NaN') {
                    if (session('data')['CustomersType'] == 0 & $flag == 0) {
                        $info[$row]['Rate'] = $val['UserRate'];
                        $info[$row]['ratefee'] = fee2yuan($val['UserRate_fee']);
                    } else if ($type == 1 & $flag == 1) {
                        $info[$row]['Rate'] = $val['Rate'];
                        // $info[$row]['Rate_fee']=fee2yuan($val['Rate_fee']);
                    } else if (session('data')['CustomersType'] == 1 & $flag == 0) {
                        $info[$row]['Rate'] = $val['Rate'];
                        // $info[$row]['Rate_fee']=fee2yuan($val['Rate_fee']);
                    }
                } else if ($_POST['Customer'] == 'NaN' & $TopSysNo == 'NaN' & $Top == 'NaN') {
                    if ($type == 1 & $flag == 1) {
                        $info[$row]['Rate'] = $val['Rate'];
                    } else if (session('data')['CustomersType'] == 1 & $flag == 0) {
                        $info[$row]['Rate'] = $val['Rate'];
                    } else {
                        $info[$row]['Rate'] = $val['Rate'];
                        $info[$row]['Rate_fee'] = fee2yuan($val['Rate_fee']);
                    }
                } else if ($SysNo != 'NaN') {
                    if ($type == 1 & $flag == 1) {
                        $info[$row]['Rate'] = $val['Rate'];
                    } else if (session('data')['CustomersType'] == 1 & $flag == 0) {
                        $info[$row]['Rate'] = $val['Rate'];
                    } else {
                        $info[$row]['Rate'] = $val['Rate'];
                        $info[$row]['Rate_fee'] = fee2yuan($val['Rate_fee']);
                    }
                } else if ($Top != 'NaN') {
                    if ($type == 1 & $flag == 1) {
                        $info[$row]['UserRate'] = $val['UserRate'];
                    } else if (session('data')['CustomersType'] == 1 & $flag == 0) {
                        $info[$row]['UserRate'] = $val['UserRate'];
                    } else {
                        $info[$row]['UserRate'] = $val['UserRate'];
                        $info[$row]['UserRate_fee'] = fee2yuan($val['UserRate_fee']);
                    }
                } else if ($Customer != 'NaN') {
                    if ($type == 1 & $flag == 1) {
                        $info[$row]['UserRate'] = $val['UserRate'];
                    } else if (session('data')['CustomersType'] == 1 & $flag == 0) {
                        $info[$row]['UserRate'] = $val['UserRate'];
                    } else {
                        $info[$row]['UserRate'] = $val['UserRate'];
                        $info[$row]['UserRate_fee'] = fee2yuan($val['UserRate_fee']);
                    }
                } else if ($CustomerSysNo != 'NaN') {
                    if ($type == 1 & $flag == 1) {
                        $info[$row]['UserRate'] = $val['UserRate'];
                    } else if (session('data')['CustomersType'] == 1 & $flag == 0) {
                        $info[$row]['UserRate'] = $val['UserRate'];
                    } else {
                        $info[$row]['UserRate'] = $val['UserRate'];
                        $info[$row]['UserRate_fee'] = fee2yuan($val['UserRate_fee']);
                    }
                } else {
                    if ($type == 1 & $flag == 1) {
                        $info[$row]['Rate'] = $val['Rate'];
                    } else if (session('data')['CustomersType'] == 1 & $flag == 0) {
                        $info[$row]['Rate'] = $val['Rate'];
                    } else {
                        $info[$row]['Rate'] = $val['Rate'];
                        $info[$row]['Rate_fee'] = fee2yuan($val['Rate_fee']);
                    }
                }
                $info[$row]['Cash_fee_type'] = "人民币";
                $info[$row]['Tradecount'] = $val['Tradecount'];
            }

            foreach ($info as $field => $v) {

                if (session('data')['CustomersType'] == 1 & $flag == 0) {
                    if ($field == 'CustomerName') {
                        $headArr[] = '员工名称';
                    }
                }
                if (session('data')['CustomersType'] == 0 & $flag == 0) {
                    if ($SysNo != 'NaN') {
                        if ($field == 'CustomerName') {
                            $headArr[] = '员工名称';
                        }
                    } else if ($Customer != 'NaN') {
                        if ($field == 'CustomerName') {
                            $headArr[] = '商户名称';
                        }
                    } else {
                        if ($field == 'CustomerName') {
                            $headArr[] = '商户名称';
                        }
                    }
                }
                if ($type == 0 & $flag == 1) {
                    if ($SysNo != 'NaN') {
                        if ($field == 'CustomerName') {
                            $headArr[] = '员工名称';
                        }
                    } else if ($Customer != 'NaN') {
                        if ($field == 'CustomerName') {
                            $headArr[] = '商户名称';
                        }
                    } else {
                        if ($field == 'CustomerName') {
                            $headArr[] = '商户名称';
                        }
                    }
                }
                if ($type == 1 & $flag == 1) {
                    if ($field == 'CustomerName') {
                        $headArr[] = '员工名称';
                    }
                }
                if ($field == 'Cash_fee') {
                    $headArr[] = '交易金额';
                }
                if ($field == 'fee') {
                    $headArr[] = '实际交易金额';
                }

                if ($type == 1 & $flag == 1) {
                    if ($field == 'Rate') {
                        $headArr[] = '员工费率';
                    }
                    // if($field == 'Rate_fee'){
                    //     $headArr[]='返佣';
                    // }
                } else if ($type == 0 & $flag == 1) {
                    if ($SysNo != 'NaN') {
                        if ($field == 'UserRate') {
                            $headArr[] = '商户费率';
                        }
                        if ($field == 'UserRate_fee') {
                            $headArr[] = '返佣';
                        }
                    } else if ($Customer != 'NaN') {
                        if ($field == 'Rate') {
                            $headArr[] = '上级费率';
                        }
                        if ($field == 'Rate_fee') {
                            $headArr[] = '返佣';
                        }
                    } else if ($Top != 'NaN') {
                        if ($field == 'Rate') {
                            $headArr[] = '上级费率';
                        }
                        if ($field == 'Rate_fee') {
                            $headArr[] = '返佣';
                        }
                    } else {
                        if ($field == 'UserRate') {
                            $headArr[] = '商户费率';
                        }
                        if ($field == 'UserRate_fee') {
                            $headArr[] = '返佣';
                        }
                    }
                } else if (session('data')['CustomersType'] == 1 & $flag == 0) {
                    if ($TopSysNo != 'NaN') {
                        if ($field == 'Rate') {
                            $headArr[] = '员工费率';
                        }
                        // if($field == 'Rate_fee'){
                        //     $headArr[]='返佣';
                        // }
                    } else {
                        if ($field == 'UserRate') {
                            $headArr[] = '商户费率';
                        }
                        // if($field == 'UserRate_fee'){
                        //     $headArr[]='返佣';
                        // }
                    }
                } else if (session('data')['CustomersType'] == 0 & $flag == 0) {

                    if ($SysNo != 'NaN') {
                        if ($field == 'UserRate') {
                            $headArr[] = '商户费率';
                        }
                        if ($field == 'UserRate_fee') {
                            $headArr[] = '返佣';
                        }
                    } else if ($CustomerSysNo != 'NaN') {
                        if ($field == 'UserRate') {
                            $headArr[] = '商户费率';
                        }
                        if ($field == 'UserRate_fee') {
                            $headArr[] = '返佣';
                        }
                    } else {
                        if ($field == 'Rate') {
                            $headArr[] = '上级费率';
                        }
                        if ($field == 'Rate_fee') {
                            $headArr[] = '返佣';
                        }
                    }
                }
                if ($field == 'Cash_fee_type') {
                    $headArr[] = '交易币种';
                }
                if ($field == 'Tradecount') {
                    $headArr[] = '交易笔数';
                }
            }
            $filename = "交易费率订单汇总报表";
        }
        $this->getExcel($filename, $headArr, $info);
    }

    public function getExcel($fileName, $headArr, $info) {
        //导入PHPExcel类库，因为PHPExcel没有用命名空间，只能inport导入
        import("Org.Util.PHPExcel");
        import("Org.Util.PHPExcel.Writer.Excel5");
        import("Org.Util.PHPExcel.IOFactory.php");
        $date = date("Y-m-d", time());
        $fileName .= "_{$date}.xls";
        //创建PHPExcel对象，注意，不能少了\
        $objPHPExcel = new \PHPExcel();
        $objProps = $objPHPExcel->getProperties();
        //设置表头
        $key = ord("A");
        //print_r($headArr);exit;
        foreach ($headArr as $v) {
            $colum = chr($key);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum . '1', $v);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum . '1', $v);
            $key += 1;
        }

        $column = 2;
        $objActSheet = $objPHPExcel->getActiveSheet();

        foreach ($info as $key => $rows) { //行写入
            $span = ord("A");
            foreach ($rows as $keyName => $value) {// 列写入
                $j = chr($span);
                $objActSheet->setCellValue($j . $column, $value);
                $span++;
            }
            $column++;
        }

        $fileName = iconv("utf-8", "gb2312", $fileName);
        //重命名表
        //$objPHPExcel->getActiveSheet()->setTitle('test');
        //设置活动单指数到第一个表,所以Excel打开这是第一个表
        $objPHPExcel->setActiveSheetIndex(0);
        ob_end_clean();//清除缓冲区,避免乱码
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=\"$fileName\"");
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output'); //文件通过浏览器下载
        exit;
    }
} 