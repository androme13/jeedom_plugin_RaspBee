<?php
/* This file is part of Plugin RaspBEE for jeedom.
 *
 * Plugin RaspBEE for jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Plugin RaspBEE for jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Plugin RaspBEE for jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

 require_once dirname(__FILE__) . "/../../../../core/php/core.inc.php";
 
 class colorHelper{	
 
 
 	public function __construct() {
       	
    }
 
	public function HSV2RGB($iH, $iS, $iV) {
		if($iH < 0)   $iH = 0;   // Hue:
		if($iH > 360) $iH = 360; //   0-360
		if($iS < 0)   $iS = 0;   // Saturation:
		if($iS > 100) $iS = 100; //   0-100
		if($iV < 0)   $iV = 0;   // Lightness:
		if($iV > 100) $iV = 100; //   0-100
		$dS = $iS/100.0; // Saturation: 0.0-1.0
		$dV = $iV/100.0; // Lightness:  0.0-1.0
		$dC = $dV*$dS;   // Chroma:     0.0-1.0
		$dH = $iH/60.0;  // H-Prime:    0.0-6.0
		$dT = $dH;       // Temp variable
		while($dT >= 2.0) $dT -= 2.0; // php modulus does not work with float
		$dX = $dC*(1-abs($dT-1));     // as used in the Wikipedia link
		switch(floor($dH)) {
		case 0:
			$dR = $dC; $dG = $dX; $dB = 0.0; break;
		case 1:
			$dR = $dX; $dG = $dC; $dB = 0.0; break;
		case 2:
			$dR = 0.0; $dG = $dC; $dB = $dX; break;
		case 3:
			$dR = 0.0; $dG = $dX; $dB = $dC; break;
		case 4:
			$dR = $dX; $dG = 0.0; $dB = $dC; break;
		case 5:
			$dR = $dC; $dG = 0.0; $dB = $dX; break;
		default:
			$dR = 0.0; $dG = 0.0; $dB = 0.0; break;
		}
		$dM  = $dV - $dC;
		$dR += $dM; $dG += $dM; $dB += $dM;
		$dR *= 255; $dG *= 255; $dB *= 255;
		return array(round($dR),round($dG),round($dB));
	}
	
	public function RGB2XY($R,$G,$B,$isFloat=false){
	//error_log("RGBtoXY:",3,"/tmp/prob.txt");		
		if ($isFloat==false){
			$R=$R/256;
			$G=$G/256;
			$B=$B/256;
		}
		$X = 0.4124*$R + 0.3576*$G + 0.1805*$B;
		$Y = 0.2126*$R + 0.7152*$G + 0.0722*$B;
		$Z = 0.0193*$R + 0.1192*$G + 0.9505*$B;
		$x = $X / ($X + $Y + $Z);
		$y = $Y / ($X + $Y + $Z);
	return 	array('x' => $x,'y' => $y);
	}
	
	public function XY2RGB($x,$y){		
		$R = 3.240479*(($x*$y)/$y) + -1.537150*$y + -0.498535*(((1-$x-$y)*$y)/$y);
		$G = -0.969256*(($x*$y)/$y) + 1.875992*$y + 0.041556*(((1-$x-$y)*$y)/$y);
		$B = 0.055648*(($x*$y)/$y) + -0.204043*$y + 1.057311*(((1-$x-$y)*$y)/$y);
		return array('r' => $R,'g' => $G,'b' => $B);
	}

	public function HSL2RGB($hsl) {
		$h =$hsl[0]/365;
		$s = $hsl[1]/256;
		$l = $hsl[2]/256;
		$r; 
		$g; 
		$b;
		$c = ( 1 - abs( 2 * $l - 1 ) ) * $s;
		$x = $c * ( 1 - abs( fmod( ( $h / 60 ), 2 ) - 1 ) );
		$m = $l - ( $c / 2 );
		if ( $h < 60 ) {
			$r = $c;
			$g = $x;
			$b = 0;
		} else if ( $h < 120 ) {
			$r = $x;
			$g = $c;
			$b = 0;			
		} else if ( $h < 180 ) {
			$r = 0;
			$g = $c;
			$b = $x;					
		} else if ( $h < 240 ) {
			$r = 0;
			$g = $x;
			$b = $c;
		} else if ( $h < 300 ) {
			$r = $x;
			$g = 0;
			$b = $c;
		} else {
			$r = $c;
			$g = 0;
			$b = $x;
		}
		$r = ( $r + $m ) * 255;
		$g = ( $g + $m ) * 255;
		$b = ( $b + $m  ) * 255;
		return array( floor( $r ), floor( $g ), floor( $b ) );
	}
	
	public function HEX2RGB(string $hex){
		$hex = ltrim($hex, '#');
		if(strlen($hex) == 3)
			return array ('r' => hexdec($hex[0].$hex[0]),'g' => hexdec($hex[1].$hex[1]),'b' => hexdec($hex[2].$hex[2]));			
		else
			return array ('r' => hexdec($hex[0].$hex[1]),'g' => hexdec($hex[2].$hex[3]),'b' => hexdec($hex[4].$hex[5]));
	}

	public function RGB2HEX(array $rgb){
		return '#'
			. sprintf('%02x', $rgb[0])
			. sprintf('%02x', $rgb[1])
			. sprintf('%02x', $rgb[2]);
	}
}
?>