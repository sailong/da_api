<?php //ͼ��
class image_funAction extends AdminAuthAction{
	 var $X;//ͼƬ��СX�� 
	 var $Y;//ͼƬ��СY�� 
	 var $R;//��ӰɫRֵ 
	 var $G;//...G. 
	 var $B;//...B. 
	 var $TRANSPARENT;//�Ƿ�͸��1��0 
	 var $IMAGE;//ͼƬ���� 
	 //------------------- 
	 var $ARRAYSPLIT;//ָ�����ڷָ���ֵ�ķ��� 
	 var $ITEMARRAY;//��ֵ 
	 var $REPORTTYPE;//ͼ������,1Ϊ������2Ϊ������3Ϊ������ 
	 var $BORDER;//���� 
	 //------------------- 
	 var $FONTSIZE;//�����С 
	 var $FONTCOLOR;//������ɫ 
	 
	 var $numX = 1;//X����ʼ�̶�ֵ
	 var $stepX = 1;//X��ÿһ���̶ȼ��ֵ
	 
	 public function _basic()	
	 {
	 	parent::_basic();
	 }
	 //--------�������ú��� 
	 public function setImage($SizeX,$SizeY,$R,$G,$B,$Transparent){ 
	  $this->X=$SizeX;  
	  $this->Y=$SizeY;  
	  $this->R=$R;  
	  $this->G=$G;  
	  $this->B=$B;  
	  $this->TRANSPARENT=$Transparent;  
	 }  
	 public function setItem($ArraySplit,$ItemArray,$ReportType,$Border){  
	  $this->ARRAYSPLIT=$ArraySplit;  
	  $this->ITEMARRAY=$ItemArray;  
	  $this->REPORTTYPE=$ReportType;  
	  $this->BORDER=$Border;  
	 }  
	 public function setFont($FontSize){  
	  $this->FONTSIZE=$FontSize;  
	 }  
	 //X��̶�ֵ����
	 public function setX($numX = 1, $stepX = 1){
	  $this->numX = $numX;
	  $this->stepX = $stepX;
	 }
	 //----------------����  
	 public function PrintReport(){ 
	   //����������С  
	   $this->IMAGE=ImageCreate($this->X,$this->Y);  
	   //�趨��������ɫ  
	   $background=ImageColorAllocate($this->IMAGE,$this->R,$this->G,$this->B);  
	   if($this->TRANSPARENT=="1"){  
		//��Ӱ͸��  
		Imagecolortransparent($this->IMAGE,$background);  
	   }else{  
		//�粻Ҫ͸��ʱ����䱳��ɫ  
		ImageFilledRectangle($this->IMAGE,0,0,$this->X,$this->Y,$background);  
	   }  
	   //����������С����ɫ  
	   $this->FONTCOLOR=ImageColorAllocate($this->IMAGE,255-$this->R,255-$this->G,255-$this->B);  
	   Switch ($this->REPORTTYPE){  
		case "0":  
		 break;  
		case "1":  
		 $this->imageColumnS();  
		 break;
		case "2":
		 $this->imageColumnH();  
		 break;  
		case "3":  
		 $this->imageLine();  
		 break;
		case "4":  
		 $this->imageCircle();  
		 break;    
	   }  
	   $this->printXY();  
	   $this->printAll();  
	 }  
	 //-----------��ӡXY������  
	 public function printXY(){  
	   $rulerY = $rulerX = "";
	   //��XY������*/  
	   $color=ImageColorAllocate($this->IMAGE,255-$this->R,255-$this->G,255-$this->B);  
	   $xx=$this->X/10;  
	   $yy=$this->Y-$this->Y/10;
	   ImageLine($this->IMAGE,$this->BORDER,$this->BORDER,$this->BORDER,$this->Y-$this->BORDER,$color);//X��  
	   ImageLine($this->IMAGE,$this->BORDER,$this->Y-$this->BORDER,$this->X-$this->BORDER,$this->Y-$this->BORDER,$color);//y��  
	   imagestring($this->IMAGE, $this->FONTSIZE, $this->BORDER-2, $this->Y-$this->BORDER+5, "0", $color);
	   //Y���Ͽ̶�  
	   $rulerY=$this->Y-$this->BORDER;  
	   $i = 0;
	   while($rulerY>$this->BORDER*2){  
		$rulerY=$rulerY-$this->BORDER;  
		ImageLine($this->IMAGE,$this->BORDER,$rulerY,$this->BORDER-2,$rulerY,$color);  
		
		if($this->REPORTTYPE == 2){//����ͼ
		 imagestring($this->IMAGE, $this->FONTSIZE, $this->BORDER-10, $rulerY-2-$this->BORDER*($i+.5), $this->numX, $color);
		 $this->numX += $this->stepX;
		}
		$i++;
	   }  
	   //X���Ͽ̶�  
	   $rulerX=$rulerX+$this->BORDER;  
	   $i = 0;
	   while($rulerX<($this->X-$this->BORDER*2)){  
		$rulerX=$rulerX+$this->BORDER;  
		//ImageLine($this->IMAGE,$this->BORDER,10,$this->BORDER+10,10,$color);  
		ImageLine($this->IMAGE,$rulerX,$this->Y-$this->BORDER,$rulerX,$this->Y-$this->BORDER+2,$color);  
		
		//�̶�ֵ
		if($this->REPORTTYPE == 1){//����ͼ
		 imagestring($this->IMAGE, $this->FONTSIZE, $rulerX-2+$this->BORDER*($i+.5), $this->Y-$this->BORDER+5, $this->numX, $color);
		 $this->numX += $this->stepX;
		}else if($this->REPORTTYPE == 3){//����ͼ
		 imagestring($this->IMAGE, $this->FONTSIZE, $rulerX-2, $this->Y-$this->BORDER+5, $this->numX, $color);
		 $this->numX += $this->stepX;
		}
		$i++;
	   }  
	 }  
	 
	 //--------------������ͼ  
	 public function imageColumnS(){  
	   $item_array=Split($this->ARRAYSPLIT,$this->ITEMARRAY);  
	   $num=Count($item_array);  
	   $item_max=0;  
	   for ($i=0;$i<$num;$i++){ 
		$item_max=Max($item_max,$item_array[$i]); 
	   } 
	   $xx=$this->BORDER*2;  
	   //������ͼ  
	   for ($i=0;$i<$num;$i++){ 
		srand((double)microtime()*1000000); 
		if($this->R!=255 && $this->G!=255 && $this->B!=255){  
		 $R=Rand($this->R,200);  
		 $G=Rand($this->G,200);  
		 $B=Rand($this->B,200);  
		}else{  
		 $R=Rand(50,200);  
		 $G=Rand(50,200);  
		 $B=Rand(50,200);  
		}
		$color=ImageColorAllocate($this->IMAGE,$R,$G,$B);  
		//���θ߶�  
		$height=($this->Y-$this->BORDER)-($this->Y-$this->BORDER*2)*($item_array[$i]/$item_max);  
		ImageFilledRectangle($this->IMAGE,$xx,$height,$xx+$this->BORDER,$this->Y-$this->BORDER,$color);  
		ImageString($this->IMAGE,$this->FONTSIZE,$xx,$height-$this->BORDER,$item_array[$i],$this->FONTCOLOR);  
		//���ڼ��  
		$xx=$xx+$this->BORDER*2;  
	   }  
	 }  
	 //-----------������ͼ  
	 public function imageColumnH(){ 
	   $item_array=Split($this->ARRAYSPLIT,$this->ITEMARRAY);  
	   $num=Count($item_array);  
	   $item_max=0;  
	   for ($i=0;$i<$num;$i++){
		$item_max=Max($item_max,$item_array[$i]); 
	   }
	   $yy=$this->Y-$this->BORDER*2;  
	   //������ͼ  
	   for ($i=0;$i<$num;$i++){ 
		srand((double)microtime()*1000000); 
		if($this->R!=255 && $this->G!=255 && $this->B!=255){
		 $R=Rand($this->R,200);  
		 $G=Rand($this->G,200);  
		 $B=Rand($this->B,200);  
		}else{  
		 $R=Rand(50,200);  
		 $G=Rand(50,200);  
		 $B=Rand(50,200);  
		}  
		$color=ImageColorAllocate($this->IMAGE,$R,$G,$B);  
		//���γ���  
		$leight=($this->X-$this->BORDER*2)*($item_array[$i]/$item_max);  
		$leight = $leight < $this->BORDER ? $this->BORDER : $leight;
		ImageFilledRectangle($this->IMAGE,$this->BORDER,$yy-$this->BORDER,$leight,$yy,$color);  
		ImageString($this->IMAGE,$this->FONTSIZE,$leight+2,$yy-$this->BORDER,$item_array[$i],$this->FONTCOLOR);  
		//���ڼ��  
		$yy=$yy-$this->BORDER*2;  
	   }  
	 }
	 //--------------����ͼ  
	 public function imageLine(){  
	   $item_array=Split($this->ARRAYSPLIT,$this->ITEMARRAY);  
	   $num=Count($item_array);  
	   $item_max=0;  
	   for ($i=0;$i<$num;$i++){ 
		$item_max=Max($item_max,$item_array[$i]); 
	   } 
	   $xx=$this->BORDER;  
	   //������ͼ  
	   for ($i=0;$i<$num;$i++){ 
		srand((double)microtime()*1000000); 
		if($this->R!=255 && $this->G!=255 && $this->B!=255){  
		 $R=Rand($this->R,200);  
		 $G=Rand($this->G,200);  
		 $B=Rand($this->B,200);  
		}else{  
		 $R=Rand(50,200);  
		 $G=Rand(50,200);  
		 $B=Rand(50,200);  
		}  
		$color=ImageColorAllocate($this->IMAGE,$R,$G,$B);  
		//���θ߶�  
		$height_now=($this->Y-$this->BORDER)-($this->Y-$this->BORDER*2)*($item_array[$i]/$item_max);  
		if($i!="0")
		 ImageLine($this->IMAGE,$xx-$this->BORDER,$height_next,$xx,$height_now,$color);  
		  
		ImageString($this->IMAGE,$this->FONTSIZE,$xx+2,$height_now-$this->BORDER/2,$item_array[$i],$this->FONTCOLOR);  
		$height_next=$height_now;  
		//���ڼ��  
		$xx=$xx+$this->BORDER;  
	   }  
	 }  
	 //--------------��״ͼ
	 public function imageCircle(){
	   $total = 0;
	   $item_array=Split($this->ARRAYSPLIT,$this->ITEMARRAY);  
	   $num=Count($item_array);  
	   $item_max=0;  
	   for ($i=0;$i<$num;$i++){ 
		$item_max=Max($item_max,$item_array[$i]);
		$total += $item_array[$i];
	   } 
	   $yy=$this->Y-$this->BORDER*2;  
	   
	   //����״ͼ����Ӱ����  
	   $e=0;
	   for ($i=0;$i<$num;$i++){ 
		srand((double)microtime()*1000000); 
		if($this->R!=255 && $this->G!=255 && $this->B!=255){  
		 $R=Rand($this->R,200);  
		 $G=Rand($this->G,200);  
		 $B=Rand($this->B,200);  
		}else{  
		 $R=Rand(50,200);  
		 $G=Rand(50,200);  
		 $B=Rand(50,200);  
		}
		$s=$e;
		$leight=$item_array[$i]/$total*360;
		$e=$s+$leight; 
		$color=ImageColorAllocate($this->IMAGE,$R,$G,$B);
		$colorarray[$i]=$color;  
		//��Բ   
		for ($j = 90; $j > 70; $j--) imagefilledarc($this->IMAGE, 110, $j, 200, 100, $s, $e, $color, IMG_ARC_PIE);  
		 //imagefilledarc($this->IMAGE, 110, 70, 200, 100, $s, $e, $color, IMG_ARC_PIE);  
		 //ImageFilledRectangle($this->IMAGE,$this->BORDER,$yy-$this->BORDER,$leight,$yy,$color);  
		 //ImageString($this->IMAGE,$this->FONTSIZE,$leight+2,$yy-$this->BORDER,$item_array[$i],$this->FONTCOLOR);  
		 //���ڼ��  
		 $yy=$yy-$this->BORDER*2;  
	   }
	   
	   //����״ͼ�ı��沿��
	   $e=0;
	   for ($i=0;$i<$num;$i++){ 
		srand((double)microtime()*1000000); 
		if($this->R!=255 && $this->G!=255 && $this->B!=255){  
		 $R=Rand($this->R,200);  
		 $G=Rand($this->G,200);  
		 $B=Rand($this->B,200);  
		}else{  
		 $R=Rand(50,200);  
		 $G=Rand(50,200);  
		 $B=Rand(50,200);  
		}
		$s=$e;
		$leight=$item_array[$i]/$total*360;
		$e=$s+$leight; 
		//$color=$colorarray[$i];
		$color=ImageColorAllocate($this->IMAGE,$R,$G,$B);  
		//��Բ   
		//for ($j = 90; $j > 70; $j--) imagefilledarc($this->IMAGE, 110, $j, 200, 100, $s, $e, $color, IMG_ARC_PIE);  
		imagefilledarc($this->IMAGE, 110, 70, 200, 100, $s, $e, $color, IMG_ARC_PIE);  
	   }  
	 }  
	 //--------------��ɴ�ӡͼ��  
	 public function printAll(){  
	  ImagePNG($this->IMAGE);
	  ImageDestroy($this->IMAGE);  
	 }  
	 //--------------����  
	 public function debug(){  
	  echo "X:".$this->X."<br/>Y:".$this->Y;  
	  echo "<br/>BORDER:".$this->BORDER;  
	  $item_array=split($this->ARRAYSPLIT,$this->ITEMARRAY);  
	  $num=Count($item_array);  
	  echo "<br/>��ֵ����:".$num."<br/>��ֵ:";  
	  for ($i=0;$i<$num;$i++){ 
	   echo "<br/>".$item_array[$i];  
	  }  
	 } 
	public function view_img(){
		//$this->debug();
		Header( "Content-type:image/png");
		//$report=new ImageReport;
		$this->setImage(600,500,255,255,255,1);//����(��,��,��ӰɫR,G,B,�Ƿ�͸��1��0)  
		$temparray="0,260,400,124,48,720,122,440,475";//��ֵ,��ָ�����Ÿ���  
		$this->setItem(',',$temparray,1,23);//����(�ָ���ֵ��ָ������,��ֵ����,��ʽ1Ϊ����ͼ2Ϊ����ͼ3Ϊ����ͼ4Ϊ��ͼ,����)  
		$this->setFont(10);//�����С1-10  
		$this->setX(1,10);//����X��̶�ֵ(��ʼ�̶�ֵ=1���̶ȼ��ֵ=1)
		$this->PrintReport();
	}

}
//$report->debug();//��ʽ֮��
/*
Header( "Content-type:image/png");
$report=new ImageReport;
$report->setImage(600,500,255,255,255,1);//����(��,��,��ӰɫR,G,B,�Ƿ�͸��1��0)  
$temparray="0,260,400,124,48,720,122,440,475";//��ֵ,��ָ�����Ÿ���  
$report->setItem(',',$temparray,3,23);//����(�ָ���ֵ��ָ������,��ֵ����,��ʽ1Ϊ����ͼ2Ϊ����ͼ3Ϊ����ͼ4Ϊ��ͼ,����)  
$report->setFont(1);//�����С1-10  
//$report->setX(1,1);//����X��̶�ֵ(��ʼ�̶�ֵ=1���̶ȼ��ֵ=1)
$report->PrintReport();
*/
?>