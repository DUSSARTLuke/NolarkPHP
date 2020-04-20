<?php
session_start();
header ("Content-type: png");
$image = imagecreate(320, 100);

$blanc = imagecolorallocate($image, 255, 255, 255);
$noir = imagecolorallocate($image, 0, 0, 0);
$gris = imagecolorallocate($image, 200,200,200);
$jaune = imagecolorallocate($image, 255, 255, 0);
$rouge = imagecolorallocate($image, 200, 39, 45);
$vert = imagecolorallocate($image, 45, 255, 39);
$cyan = imagecolorallocate($image, 0, 255, 255);
$magenta = imagecolorallocate($image, 200, 0, 200);
$orange = imagecolorallocate($image, 255, 160, 0);
$bleu = imagecolorallocate($image, 60, 75, 200);
$bleuclair = imagecolorallocate($image, 156, 227, 254);
$vertf = imagecolorallocate($image, 20, 140, 17);
$Acyan = imagecolorallocatealpha($image, 0, 255, 255, 80);
$Amagenta = imagecolorallocatealpha($image, 255, 0, 255, 80);
$Aorange = imagecolorallocatealpha($image, 255, 128, 0, 80);
$Ableu = imagecolorallocatealpha($image, 39, 45, 200, 80);
$Ableuclair = imagecolorallocatealpha($image, 156, 227, 254, 80);

$colors = Array($vert, $noir, $jaune, $blanc, $rouge, $cyan, $magenta, $orange, $bleu, $bleuclair, $gris, $vertf, $Ableu, $Ableuclair, $Acyan, $Amagenta, $Aorange);
$Tcolors = count($colors);

//couleurs autorisées pour les caractères
$Lcolors = Array($noir, $rouge, $magenta, $bleu, $vertf);
$TLcolors = count($Lcolors);

$polices = Array('baveuse3d'); //Pensez à en rajouter !!
$Tpolices = count($polices);

//définition des caractères autorisés.
$carac = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
$Tcarac = strlen($carac);

//définition des lignes noires
$nb_lignesN = mt_rand(3,7);
$i = 1;
while($i<=$nb_lignesN)
{
	ImageLine($image, mt_rand(0,40), mt_rand(0,100), mt_rand(280, 320), mt_rand(0,100), $noir);
	$i++;
}

//définition des lignes colorées.
$nb_lignesC = mt_rand(3,7);
$j = 1;
while($j<=$nb_lignesC)
{
	ImageLine($image, mt_rand(0,40), mt_rand(0,100), mt_rand(280,320), mt_rand(0,100), $colors[mt_rand(0,$Tcolors-1)]);
	$j++;
}

//définition des ellipses
$nb_ellipses = mt_rand(1,6);
$k = 1;
while($k<= $nb_ellipses)
{
	ImageEllipse($image, mt_rand(0,320), mt_rand(0,100), 25+mt_rand(0,15), 25+mt_rand(0,15), $colors[mt_rand(0,$Tcolors-1)]);
	$k++;
}

//définition des triangles
$nb_triangles = mt_rand(1,6);
$l = 1;
while($l<=$nb_triangles)
{
	$array = Array(mt_rand(0,300), mt_rand(0,100), mt_rand(0,300), mt_rand(0,100), mt_rand(0,300), mt_rand(0,100));
	ImagePolygon($image, $array, 3, $colors[mt_rand(0,$Tcolors-1)]);
	$l++;
}

$aupifcolor = $Lcolors[mt_rand(0,$TLcolors-1)]; //la couleur des caractères
$ecart = 300/10+4; //écart entre les caractères

$_SESSION['captcha'] = ''; 

$m = 0;
while($m <= 7)
{
	$lettre = $carac[mt_rand(0, $Tcarac-1)]; //choix de lettre
	$_SESSION['captcha'] .= $lettre; //stockage
	$taille = mt_rand(35,45); //taille
	$angle = mt_rand(-35,35); //angle
	$y = mt_rand(55, 60); //ordonnée
	$police = $polices[mt_rand(0, $Tpolices-1)]; //police :p
	
	imagettftext($image, $taille, $angle, $ecart*$i+15, $y, $aupifcolor, 'polices/'.$police.'.ttf', $lettre);
	$m++;
}

imagepng($image);