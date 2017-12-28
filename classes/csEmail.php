<?php

Class csEmail { 

	function sendEmail($emailTo, $emailSubject, $emailMessage, $emailTipoNotificacion, $emailBCC='') {
		try {

			$emailHeader  = 'MIME-Version: 1.0' . "\r\n";
			$emailHeader .= 'Content-type: text/html; charset=utf-8' . "\r\n";
			//$emailHeader .= 'From: jaimerodriguezvillalta@gmail.com' . "\r\n";
			$emailHeader .= 'From: Información Guateplast <no-reply@guateplast.com>' . "\r\n";
			if($emailBCC!='') { $emailHeader .= 'Bcc: ' . $emailBCC . "\r\n"; }
			//else { $emailHeader .= 'Bcc: jaimerodriguezvillalta@gmail.com' . "\r\n"; }

			$parsedMessage = '
			<style>
			.buttons {
			    cursor: pointer;
			    background-color: #72b842;
			    border-color: #5ea32e;
			    padding: 10px 16px;
			    font-size: 14px;
			    border-radius: 6px;
			    color: #fff;
			    border: 1px solid transparent;
			    -webkit-appearance: button;
			    font: inherit;
			}
			</style>
			<div>
				<div>
					<div class="x_ppmail">
						<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
							<tbody>
								<tr>
									<td></td>
								</tr>
							</tbody>
						</table>
						<table align="center" border="0" cellpadding="0" cellspacing="0" width="600">
							<tbody>
								<tr valign="top">
									<td width="100%">
										<table align="center" border="0" cellpadding="0" cellspacing="0" width="600" style="color:#333333!important; font-family:arial,helvetica,sans-serif; font-size:12px">
											<tbody>
												<tr valign="top">
													<td><img src="http://guateplast-apg.com/images/LogoGuatePlastTransparente.png" width=50px border="0" alt="Guateplast logo"></td>
													<td valign="middle" align="right">Notificaci&oacute;n de:<br><strong>'.$emailTipoNotificacion.'</td>
												</tr>
											</tbody>
										</table>

										<div style="margin-top:30px; color:#333!important; font-family:arial,helvetica,sans-serif; font-size:12px">

											'.$emailMessage.'

											<br><br>
											<div style="margin-top:5px; clear:both">
												<hr size="1">
											</div>
											<span style="font-size:11px; color:#333">Por favor no responda a este correo electrónico. Este buzón no se supervisa y no recibirá respuesta.</span><br>
										
										</div>
										<br>
										<br>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			';

			mail($emailTo, $emailSubject, $parsedMessage, $emailHeader);





// <!--
// <table border="0" cellpadding="0" cellspacing="0" width="98%" align="left" style="color:#666666!important; font-family:arial,helvetica,sans-serif; font-size:11px; margin-bottom:20px; clear:both">
// <tbody>
// <tr>
// <td valign="top" width="50%" align="left" style="padding-top:15px"><span style="color:#333333; font-weight:bold">Merchant</span><br>
// FTD.COM<br>
// custserv@ftd.com<br>
// 800-736-3383</td>
// <td valign="top" style="padding-top:15px"><img src="http://images.paypal.com/en_US/i/icon/icon_note_16x16.gif" border="0" alt=""><img src="http://images.paypal.com/en_US/i/scr/pixel.gif" alt="" border="0" width="3" height="1"><span style="color:#333333; font-weight:bold">Instructions to merchant</span><br>
// Please delivery before 12pm if possible, thanks!</td>
// </tr>
// <tr>
// <td valign="top" width="40%" align="left" style="padding-top:15px"><span style="font-family:arial,helvetica,sans-serif; font-size:12px; color:#333333; font-weight:bold">Shipping address</span><br>
// Telma Garcia<br>
// 15434 Sherman Way Apt 303<br>
// Van Nuys,&nbsp;CA&nbsp;91406<br>
// United States<br>
// </td>
// <td valign="top" style="padding-top:15px"><span style="color:#333333; font-weight:bold">Shipping details</span><br>
// The seller hasn’t provided any shipping details yet.</td>
// </tr>
// </tbody>
// </table>
// <table align="center" border="0" cellpadding="0" cellspacing="0" class="x_CartTable" width="100%" style="clear:both; color:#666666!important; font-family:arial,helvetica,sans-serif; font-size:11px">
// <tbody>
// <tr>
// <td width="350" align="left" style="border:1px solid #ccc; border-right:none; border-left:none; padding:5px 10px 5px 10px!important; color:#333333!important">
// Description</td>
// <td width="100" align="right" style="border:1px solid #ccc; border-right:none; border-left:none; padding:5px 10px 5px 10px!important; color:#333333!important">
// Unit price</td>
// <td width="50" align="right" style="border:1px solid #ccc; border-right:none; border-left:none; padding:5px 10px 5px 10px!important; color:#333333!important">
// Qty</td>
// <td width="80" align="right" style="border:1px solid #ccc; border-right:none; border-left:none; padding:5px 10px 5px 10px!important; color:#333333!important">
// Amount</td>
// </tr>
// <tr>
// <td align="left" style="padding:10px">FTD.com Order<br>
// </td>
// <td align="right" style="padding:10px">$152.58 USD</td>
// <td align="right" style="padding:10px">1</td>
// <td align="right" style="padding:10px">$152.58 USD</td>
// </tr>
// </tbody>
// </table>
// <table align="left" border="0" cellpadding="0" cellspacing="0" width="595" style="border-top:1px solid #ccc; border-bottom:1px solid #ccc; clear:both; color:#666666!important; font-family:arial,helvetica,sans-serif; font-size:11px">
// <tbody>
// <tr>
// <td>
// <table border="0" cellpadding="0" cellspacing="0" align="right" style="color:#666666!important; font-family:arial,helvetica,sans-serif; font-size:11px; margin-top:20px; clear:both; width:100%">
// <tbody>
// <tr>
// <td style="width:390px; text-align:right; padding:0 10px 0 0"><strong>Subtotal</strong></td>
// <td style="width:90px; text-align:right; padding:0 5px 0 0">$152.58 USD</td>
// </tr>
// <tr>
// <td style="width:390px; text-align:right; padding:0 10px 0 0"><span style="color:#333333!important; font-weight:bold">Total</span></td>
// <td style="width:90px; text-align:right; padding:0 5px 0 0">$152.58 USD</td>
// </tr>
// <tr>
// <td style="width:390px; text-align:right; padding:20px 10px 0 0"><span style="color:#333333!important; font-weight:bold">Payment</span></td>
// <td style="width:90px; text-align:right; padding:20px 5px 0 0">$152.58 USD</td>
// </tr>
// <tr>
// <td colspan=2><br></td>
// </tr>
// </tbody>
// </table>
// </td>
// </tr>
// </tbody>
// </table>
// -->

			
			return true;

		} catch(Exception $e) {
			return false;
		}
	}  //function getClientes
	
} //class csEmail

?>