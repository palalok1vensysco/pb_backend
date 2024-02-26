<?php
require('Fpdf/Fpdf.php');

class Fpdf_users extends FPDF{

  function Header(){
    if ($this->page == 1){
        $this->Image('http://app.sanskargroup.in/sanskar_staging/assets/sanskar_logo.png',10,6,40);		  
        $this->SetFont('Arial','B',20);
        //Move to the right
        $this->Cell(100);//margin left         
        $this->Cell(50,0,'SANSKAR LIST OF USERS',0,90,'C');   //width,margin top,text, 0-nosquare 1-square,center(C)
        $this->SetFont('Arial','B',12);


//        $this->Cell(25);//margin left         
//        $this->Cell(5,25,'List of Users',0,0,'C');    //width,margin top,text, 0-nosquare 1-square,margin-bottom,center(C)
//        $this->SetFont('Arial','B',9);


        //Date
        date_default_timezone_set('Asia/Kolkata');
        $currentdate = "Date: ".date("m-d-Y");  
        $this->Cell(20);
        $this->Cell(115,0,$currentdate,0,0,'R');


//        $this->Cell(50);
//        $this->SetFont('Arial','B',15);
//        $this->Cell(0,10,'List of pendrive users',0,0,'l');
        //Line break
        $this->Ln(20);
    }
      if($this->page>1){

       $this->set_table_headers();
      }
  }
  function Footer(){
    // Go to 1.5 cm from bottom
    $this->SetY(-10);
    // Select Arial italic 8
    $this->SetFont('Arial','I',8);
    // Print centered page number
    $this->Cell(0,0,'Page '.$this->PageNo(),0,0,'C');
  
}
  function set_table_headers(){


    $header = array('S.NO', 'USERNAME', 'EMAIL','C CODE', 'MOBILE', 'STATUS','WHATSAPP STATUS', 'REGISTRATION AT');
    // Colors, line width and bold font
    $this->SetFont('Arial','B',10); //table heading
    $this->SetFillColor(49,179,238);
    $this->SetTextColor(255);
    $this->SetDrawColor(0,0,0);
    $this->SetLineWidth(.2);
    // $this->SetFont('','');

    //Header
//    $w = array(15,45,55,30,20,30,20,20,35);
    $w = array(15,45,65,20,30,25,40,50);
    for($i=0;$i<count($header);$i++)
    $this->Cell($w[$i],10,$header[$i],1,0,'C',true);                  //7 colmn height increse
    $this->Ln();
  }

  function FancyTable($data){
      //echo '<pre>';print_r($data);echo '</pre>';die;
      // Colors, line width and bold font
      $this->set_table_headers();
//      $w = array(15,45,55,30,20,30,20,20,35);
      $w = array(15,45,65,20,30,25,40,50);
      // Color and font restoration
      $this->SetFillColor(224,235,255);
      $this->SetTextColor(0);
      //$this->SetFont('');
      $this->SetFont('Arial','',8); 
      // Data
      $fill = false;
      foreach($data as $row){
        $this->Cell($w[0],10,$row[0],'LR',0,'L',$fill);
        $this->Cell($w[1],10,$row[1],'LR',0,'L',$fill);
        $this->Cell($w[2],10,$row[2],'LR',0,'L',$fill);
        $this->Cell($w[3],10,$row[3],'LR',0,'L',$fill);
        $this->Cell($w[4],10,$row[4],'LR',0,'L',$fill);
        $this->Cell($w[5],10,$row[5],'LR',0,'L',$fill);
        $this->Cell($w[6],10,$row[6],'LR',0,'L',$fill);
        $this->Cell($w[7],10,$row[7],'LR',0,'L',$fill);
//        $this->Cell($w[6],4.5,$row[6],'LR',0,'L',$fill);
//        $this->Cell($w[7],4.5,$row[7],'LR',0,'L',$fill);
//        $this->Cell($w[8],4.5,$row[8],'LR',0,'L',$fill);
        $this->Ln();
        $fill = !$fill;
      }
      // Closing line
      $this->Cell(array_sum($w),0,'','T');
  }

   function pdf_out($data){//echo '<pre>';print_r($data);echo '</pre>';die;
    $this->SetFont('Arial','',8);
    $this->Header();
    $this->AddPage('L');

    $this->FancyTable($data);
    $this->Output();
   }

}

