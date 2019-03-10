<?php
Class ExcelAR extends CI_Controller{
    
    function __construct() {
        parent::__construct();
    $this->load->helper('form');
    $this->load->helper('url');
    $this->load->model('pagemodel');
    }
    
    function cetakAR(){
        $this->load->helper('url');
        $this->load->model('pagemodel');
        $this->load->library("excel");
        $object = new PHPExcel();
        $object->setActiveSheetIndex(0);
        $object->getActiveSheet()->setTitle('Sample Sheet');
        $object->getActiveSheet()->getStyle("A1:Y1")->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => '#4169E1')
                    ),
                    'font' => array(
                        'color' => array('rgb' => '#FFFFFF')
                    )
                )
            );
        $object->getActiveSheet()->getStyle("Z1:BM1")->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => '#483D8B')
                    ),
                    'font' => array(
                        'color' => array('rgb' => '#FFFFFF')
                    )
                )
            );
            
        $table_columns = array(
            "No Transaksi" , "IMO" , "No. & Size Container" , "Name CUST" , "No. Shipment 1" ,"No. Shipment 2", "No. Seal" , "Full / Empty" ,"Reefer / Dry" ,"Stuffing Date" ,"Container In / Out" ,"Origin Town" , "Destination Town" , "Vessel Name" , "Delivery From JKT (ETD)" , "Arv At Dest (ETA)" ,"Tanggal Container Masuk", "Remark","Tanggal Dooring Container" , "No. Shipment ULI HUB 1" ,"Tujuan Shipment ULI HUB 1" , "Tgl Dooring Shipment ULI HUB 1" ,"No. Shipment ULI HUB 2" ,"Tujuan Shipment ULI HUB 2" ,"Tgl Dooring Shipment ULI HUB 2" ,
            //25
            "No. Invoice Shipment 1", "Tanggal Terima Dokumen Shipment 1", "Invoice Shipment 1 Date", "Invoice Shipment 1 Amount", "No. Faktur Pajak Shipment 1", "Invoice Shipment 1 Payment Date","No. RC Shipment 1" ,"Invoice Shipment 1 Paid Amount", 
            "No. Invoice Shipment 2", "Tanggal Terima Dokumen Shipment 2", "Invoice Shipment 2 Date", "Invoice Shipment 2 Amount", "No. Faktur Pajak Shipment 2", "Invoice Shipment 2 Payment Date", "No. RC Shipment 2","Invoice Shipment 2 Paid Amount", 
            "No. Invoice ULI HUB Shipment 1", "Tanggal Terima Dokumen  ULI HUB Shipment 1", "Invoice ULI HUB Shipment 1 Date", "Invoice ULI HUB Shipment 1 Amount", "No. Faktur Pajak ULI HUB Shipment 1", "Invoice ULI HUB Shipment 1 Payment Date","No. RC ULI HUB Shipment 1", "Invoice ULI HUB Shipment 1 Paid Amount", 
            "No. Invoice ULI HUB Shipment 2", "Tanggal Terima Dokumen  ULI HUB Shipment 2", "Invoice ULI HUB Shipment 2 Date", "Invoice ULI HUB Shipment 2 Amount", "No. Faktur Pajak ULI HUB Shipment 2", "Invoice ULI HUB Shipment 2 Payment Date","No. RC Shipment 2", "Invoice ULI HUB Shipment 2 Paid Amount", 
            "No. Plug", "Invoice Plug Date", "Tanggal Terima Dokumen Plug", "Invoice Plug Amount", "Plug Payment Date","No. RC Plug", "Plug Paid Amount"
            //40
            );
        $column = 0;

        foreach($table_columns as $field)
        {
            $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
            $column++;
        }

        $AllData = $this->pagemodel->getAR();

        $excel_row = 2;

        foreach($AllData as $row)
        {
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $row->no_transaksi);
            $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row->IMO);
            $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row->no_container);
            $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row->name_cust); 
            $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $row->no_shipment); 
            $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $row->no_shipment2); 
            
            $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $row->no_seal); 
            $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, $row->full_empty); 
            $object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, $row->reefer_dry); 
            $object->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, $row->stuff_date);
            $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, $row->in_out); 
            $object->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, $row->origin_town);
            $object->getActiveSheet()->setCellValueByColumnAndRow(12, $excel_row, $row->dest_town);
            $object->getActiveSheet()->setCellValueByColumnAndRow(13, $excel_row, $row->vessel_name);
            $object->getActiveSheet()->setCellValueByColumnAndRow(14, $excel_row, $row->deliv_date);
            $object->getActiveSheet()->setCellValueByColumnAndRow(15, $excel_row, $row->arv_at_dest);
            $object->getActiveSheet()->setCellValueByColumnAndRow(16, $excel_row, $row->tgl_kon_masuk);
            $object->getActiveSheet()->setCellValueByColumnAndRow(17, $excel_row, $row->remark_op);
            $object->getActiveSheet()->setCellValueByColumnAndRow(18, $excel_row, $row->tgl_door );
            $object->getActiveSheet()->setCellValueByColumnAndRow(19, $excel_row, $row->no_ship_uli );

            $object->getActiveSheet()->setCellValueByColumnAndRow(20, $excel_row, $row->tuj_ship_uli);
            $object->getActiveSheet()->setCellValueByColumnAndRow(21, $excel_row, $row->tgl_door_uli);
            $object->getActiveSheet()->setCellValueByColumnAndRow(22, $excel_row, $row->no_ship_uli2);
            
            $object->getActiveSheet()->setCellValueByColumnAndRow(23, $excel_row, $row->tuj_ship_uli2);
            $object->getActiveSheet()->setCellValueByColumnAndRow(24, $excel_row, $row->tgl_door_uli2);
            //OP
            $object->getActiveSheet()->setCellValueByColumnAndRow(25, $excel_row, $row->no_inv_ship);
            $object->getActiveSheet()->setCellValueByColumnAndRow(26, $excel_row, $row->tgl_dok_ship);
            $object->getActiveSheet()->setCellValueByColumnAndRow(27, $excel_row, $row->tgl_inv_ship);
            $object->getActiveSheet()->setCellValueByColumnAndRow(28, $excel_row, $row->inv_ship_am);
            $object->getActiveSheet()->setCellValueByColumnAndRow(29, $excel_row, $row->no_faktur_ship);
            $object->getActiveSheet()->setCellValueByColumnAndRow(30, $excel_row, $row->tgl_inv_ship_pay);
            $object->getActiveSheet()->setCellValueByColumnAndRow(31, $excel_row, $row->rc_ship);
            $object->getActiveSheet()->setCellValueByColumnAndRow(32, $excel_row, $row->inv_ship_paid_am);

            $object->getActiveSheet()->setCellValueByColumnAndRow(33, $excel_row, $row->no_inv_ship2);
            $object->getActiveSheet()->setCellValueByColumnAndRow(34, $excel_row, $row->tgl_dok_ship2);
            $object->getActiveSheet()->setCellValueByColumnAndRow(35, $excel_row, $row->tgl_inv_ship2);
            $object->getActiveSheet()->setCellValueByColumnAndRow(36, $excel_row, $row->inv_ship_am2);
            $object->getActiveSheet()->setCellValueByColumnAndRow(37, $excel_row, $row->no_faktur_ship2); 
            $object->getActiveSheet()->setCellValueByColumnAndRow(38, $excel_row, $row->tgl_inv_ship_pay2);
            $object->getActiveSheet()->setCellValueByColumnAndRow(39, $excel_row, $row->rc_ship2); 
            $object->getActiveSheet()->setCellValueByColumnAndRow(40, $excel_row, $row->inv_ship_paid_am2);

            $object->getActiveSheet()->setCellValueByColumnAndRow(41, $excel_row, $row->no_inv_uli); 
            $object->getActiveSheet()->setCellValueByColumnAndRow(42, $excel_row, $row->tgl_dok_uli); 
            $object->getActiveSheet()->setCellValueByColumnAndRow(43, $excel_row, $row->tgl_inv_uli);
            $object->getActiveSheet()->setCellValueByColumnAndRow(44, $excel_row, $row->inv_uli_am); 
            $object->getActiveSheet()->setCellValueByColumnAndRow(45, $excel_row, $row->no_faktur_uli);
            $object->getActiveSheet()->setCellValueByColumnAndRow(46, $excel_row, $row->tgl_inv_uli_pay);
            $object->getActiveSheet()->setCellValueByColumnAndRow(47, $excel_row, $row->rc_uli);
            $object->getActiveSheet()->setCellValueByColumnAndRow(48, $excel_row, $row->inv_uli_paid_am);

            $object->getActiveSheet()->setCellValueByColumnAndRow(49, $excel_row, $row->no_inv_uli2);
            $object->getActiveSheet()->setCellValueByColumnAndRow(50, $excel_row, $row->tgl_dok_uli2);
            $object->getActiveSheet()->setCellValueByColumnAndRow(51, $excel_row, $row->tgl_inv_uli2);
            $object->getActiveSheet()->setCellValueByColumnAndRow(52, $excel_row, $row->inv_uli_am2 );
            $object->getActiveSheet()->setCellValueByColumnAndRow(53, $excel_row, $row->no_faktur_uli2);
            $object->getActiveSheet()->setCellValueByColumnAndRow(54, $excel_row, $row->tgl_inv_uli_pay2);
            $object->getActiveSheet()->setCellValueByColumnAndRow(55, $excel_row, $row->rc_uli2);
            $object->getActiveSheet()->setCellValueByColumnAndRow(56, $excel_row, $row->inv_uli_paid_am2);

            $object->getActiveSheet()->setCellValueByColumnAndRow(57, $excel_row, $row->no_plug);
            $object->getActiveSheet()->setCellValueByColumnAndRow(58, $excel_row, $row->inv_plugar_date);
            $object->getActiveSheet()->setCellValueByColumnAndRow(59, $excel_row, $row->tgl_dok_plug);
            $object->getActiveSheet()->setCellValueByColumnAndRow(60, $excel_row, $row->inv_plugar_am);
            $object->getActiveSheet()->setCellValueByColumnAndRow(61, $excel_row, $row->plug_pay_date);
            $object->getActiveSheet()->setCellValueByColumnAndRow(62, $excel_row, $row->rc_plug);
            $object->getActiveSheet()->setCellValueByColumnAndRow(63, $excel_row, $row->pay_plug_paid);
            //AR
            $excel_row++;
        }

        $object->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $object->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $object->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $object->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $object->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $object->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $object->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $object->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $object->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $object->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $object->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $object->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        $object->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
        $object->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
        $object->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
        $object->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
        $object->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
        $object->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
        $object->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
        $object->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
        $object->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
        $object->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
        $object->getActiveSheet()->getColumnDimension('W')->setAutoSize(true);
        $object->getActiveSheet()->getColumnDimension('X')->setAutoSize(true);
        $object->getActiveSheet()->getColumnDimension('Y')->setAutoSize(true);
        $object->getActiveSheet()->getColumnDimension('Z')->setAutoSize(true);
        $object->getActiveSheet()->getColumnDimension('AA')->setAutoSize(true);
        $object->getActiveSheet()->getColumnDimension('AB')->setAutoSize(true);
        $object->getActiveSheet()->getColumnDimension('AC')->setAutoSize(true);
        $object->getActiveSheet()->getColumnDimension('AD')->setAutoSize(true);
        $object->getActiveSheet()->getColumnDimension('AE')->setAutoSize(true);
        $object->getActiveSheet()->getColumnDimension('AF')->setAutoSize(true);
        $object->getActiveSheet()->getColumnDimension('AG')->setAutoSize(true);
        $object->getActiveSheet()->getColumnDimension('AH')->setAutoSize(true);
        $object->getActiveSheet()->getColumnDimension('AI')->setAutoSize(true);
        $object->getActiveSheet()->getColumnDimension('AJ')->setAutoSize(true);
        $object->getActiveSheet()->getColumnDimension('AK')->setAutoSize(true);
        $object->getActiveSheet()->getColumnDimension('AL')->setAutoSize(true);
        $object->getActiveSheet()->getColumnDimension('AM')->setAutoSize(true);
        $object->getActiveSheet()->getColumnDimension('AN')->setAutoSize(true);
        $object->getActiveSheet()->getColumnDimension('AO')->setAutoSize(true);
        $object->getActiveSheet()->getColumnDimension('AP')->setAutoSize(true);
        $object->getActiveSheet()->getColumnDimension('AQ')->setAutoSize(true);
        $object->getActiveSheet()->getColumnDimension('AR')->setAutoSize(true);
        $object->getActiveSheet()->getColumnDimension('AS')->setAutoSize(true);
        $object->getActiveSheet()->getColumnDimension('AT')->setAutoSize(true);
        $object->getActiveSheet()->getColumnDimension('AU')->setAutoSize(true);
        $object->getActiveSheet()->getColumnDimension('AV')->setAutoSize(true);
        $object->getActiveSheet()->getColumnDimension('AW')->setAutoSize(true);
        $object->getActiveSheet()->getColumnDimension('AX')->setAutoSize(true);
        $object->getActiveSheet()->getColumnDimension('AY')->setAutoSize(true);
        $object->getActiveSheet()->getColumnDimension('AZ')->setAutoSize(true);
        $object->getActiveSheet()->getColumnDimension('BA')->setAutoSize(true);
        $object->getActiveSheet()->getColumnDimension('BB')->setAutoSize(true);
        $object->getActiveSheet()->getColumnDimension('BC')->setAutoSize(true);
        $object->getActiveSheet()->getColumnDimension('BD')->setAutoSize(true);
        $object->getActiveSheet()->getColumnDimension('BE')->setAutoSize(true);
        $object->getActiveSheet()->getColumnDimension('BF')->setAutoSize(true);
        $object->getActiveSheet()->getColumnDimension('BG')->setAutoSize(true);
        $object->getActiveSheet()->getColumnDimension('BH')->setAutoSize(true);
        $object->getActiveSheet()->getColumnDimension('BI')->setAutoSize(true);
        $object->getActiveSheet()->getColumnDimension('BJ')->setAutoSize(true);
        $object->getActiveSheet()->getColumnDimension('BK')->setAutoSize(true);
        $object->getActiveSheet()->getColumnDimension('BL')->setAutoSize(true);
        $object->getActiveSheet()->getColumnDimension('BM')->setAutoSize(true);

        $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Dispotition: attachment;filename="Delivery From JKT.xls"');
        $object_writer->save('php://output');   
    }
}