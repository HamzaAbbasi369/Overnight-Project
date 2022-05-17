

<div id="step2">
	<TABLE class="rx_table">
		<TR>
			 <TD style="width: 28%;"></TD>
			 <TD style="width: 18%;">
			    Sphere   <span class="glyphicon glyphicon-question-sign" rel="tooltip" title="<b>Sphere:</b> Sphere power refers to the refractive correction inside the prescription. If the sphere value is (-) it corrects for near, if the Sphere value is (+) it corrects for far. PL or Plano means 0.00 sphere value."></span> 
			  </TD>
			  <TD style="width: 18%;">
			    Cylinder   <span class="glyphicon glyphicon-question-sign" rel="tooltip" title="<b>Cylinder:</b> Positive or negative, Cylinder power refers to the correction strength of the eye astigmatism. If your cylinder value set as PL, SPH or just blank, you don't require this value and can leave it at 0.00."></span> 
			  </TD>
			  <TD style="width: 18%;">
			    Axis   <span class="glyphicon glyphicon-question-sign" rel="tooltip" title="<b>Axis:</b> Axis is the angle of correction for astigmatism. If you don't have a Cylinder value on your prescription (different then 0.00, PL, SPH or blank) then this field is not required"></span>
			  </TD>
			  <TD  style="width: 18%;">
			    Add   <span class="glyphicon glyphicon-question-sign" rel="tooltip" title="<b>Add:</b> Add, addition, NV (near vision) refers to an addition power on top of your regular prescription in single vision or the reading portion of your bifocal or progressive lenses."></span>
			  </TD>
			</TR>
			<TR style="height:15px"><TD colspan="5"><hr class="rx_hr"></TD></TR>
			<TR>
			  <TD>Right Eye (OD)</TD>
			  <TD>
			    <select  style="width: 80px" name="od_sphere" id="od_sphere" >
			      <option value='-12'>-12.00</option><option value='-11.75'>-11.75</option><option value='-11.5'>-11.50</option><option value='-11.25'>-11.25</option><option value='-11'>-11.00</option><option value='-10.75'>-10.75</option><option value='-10.5'>-10.50</option><option value='-10.25'>-10.25</option><option value='-10'>-10.00</option><option value='-9.75'>-9.75</option><option value='-9.5'>-9.50</option><option value='-9.25'>-9.25</option><option value='-9'>-9.00</option><option value='-8.75'>-8.75</option><option value='-8.5'>-8.50</option><option value='-8.25'>-8.25</option><option value='-8'>-8.00</option><option value='-7.75'>-7.75</option><option value='-7.5'>-7.50</option><option value='-7.25'>-7.25</option><option value='-7'>-7.00</option><option value='-6.75'>-6.75</option><option value='-6.5'>-6.50</option><option value='-6.25'>-6.25</option><option value='-6'>-6.00</option><option value='-5.75'>-5.75</option><option value='-5.5'>-5.50</option><option value='-5.25'>-5.25</option><option value='-5'>-5.00</option><option value='-4.75'>-4.75</option><option value='-4.5'>-4.50</option><option value='-4.25'>-4.25</option><option value='-4'>-4.00</option><option value='-3.75'>-3.75</option><option value='-3.5'>-3.50</option><option value='-3.25'>-3.25</option><option value='-3'>-3.00</option><option value='-2.75'>-2.75</option><option value='-2.5'>-2.50</option><option value='-2.25'>-2.25</option><option value='-2'>-2.00</option><option value='-1.75'>-1.75</option><option value='-1.5'>-1.50</option><option value='-1.25'>-1.25</option><option value='-1'>-1.00</option><option value='-0.75'>-0.75</option><option value='-0.5'>-0.50</option><option value='-0.25'>-0.25</option><option value='0' selected>0.00</option><option value='0.25'>+0.25</option><option value='0.5'>+0.50</option><option value='0.75'>+0.75</option><option value='1'>+1.00</option><option value='1.25'>+1.25</option><option value='1.5'>+1.50</option><option value='1.75'>+1.75</option><option value='2'>+2.00</option><option value='2.25'>+2.25</option><option value='2.5'>+2.50</option><option value='2.75'>+2.75</option><option value='3'>+3.00</option><option value='3.25'>+3.25</option><option value='3.5'>+3.50</option><option value='3.75'>+3.75</option><option value='4'>+4.00</option><option value='4.25'>+4.25</option><option value='4.5'>+4.50</option><option value='4.75'>+4.75</option><option value='5'>+5.00</option><option value='5.25'>+5.25</option><option value='5.5'>+5.50</option><option value='5.75'>+5.75</option><option value='6'>+6.00</option><option value='6.25'>+6.25</option><option value='6.5'>+6.50</option><option value='6.75'>+6.75</option><option value='7'>+7.00</option><option value='7.25'>+7.25</option><option value='7.5'>+7.50</option><option value='7.75'>+7.75</option><option value='8'>+8.00</option><option value='8.25'>+8.25</option><option value='8.5'>+8.50</option><option value='8.75'>+8.75</option><option value='9'>+9.00</option><option value='9.25'>+9.25</option><option value='9.5'>+9.50</option><option value='9.75'>+9.75</option><option value='10'>+10.00</option><option value='10.25'>+10.25</option><option value='10.5'>+10.50</option><option value='10.75'>+10.75</option><option value='11'>+11.00</option><option value='11.25'>+11.25</option><option value='11.5'>+11.50</option><option value='11.75'>+11.75</option><option value='12'>+12.00</option>	    </select>
			  </TD>
			  <TD>
			    <select name="od_cylinder" id="od_cylinder" onchange="ChangeAxis('container_od_axis', 'od_cylinder', 'od_axis');">
			             <option value='-6'>-6.00</option><option value='-5.75'>-5.75</option><option value='-5.5'>-5.50</option><option value='-5.25'>-5.25</option><option value='-5'>-5.00</option><option value='-4.75'>-4.75</option><option value='-4.5'>-4.50</option><option value='-4.25'>-4.25</option><option value='-4'>-4.00</option><option value='-3.75'>-3.75</option><option value='-3.5'>-3.50</option><option value='-3.25'>-3.25</option><option value='-3'>-3.00</option><option value='-2.75'>-2.75</option><option value='-2.5'>-2.50</option><option value='-2.25'>-2.25</option><option value='-2'>-2.00</option><option value='-1.75'>-1.75</option><option value='-1.5'>-1.50</option><option value='-1.25'>-1.25</option><option value='-1'>-1.00</option><option value='-0.75'>-0.75</option><option value='-0.5'>-0.50</option><option value='-0.25'>-0.25</option><option value='0' selected>0.00</option><option value='0.25'>+0.25</option><option value='0.5'>+0.50</option><option value='0.75'>+0.75</option><option value='1'>+1.00</option><option value='1.25'>+1.25</option><option value='1.5'>+1.50</option><option value='1.75'>+1.75</option><option value='2'>+2.00</option><option value='2.25'>+2.25</option><option value='2.5'>+2.50</option><option value='2.75'>+2.75</option><option value='3'>+3.00</option><option value='3.25'>+3.25</option><option value='3.5'>+3.50</option><option value='3.75'>+3.75</option><option value='4'>+4.00</option><option value='4.25'>+4.25</option><option value='4.5'>+4.50</option><option value='4.75'>+4.75</option><option value='5'>+5.00</option><option value='5.25'>+5.25</option><option value='5.5'>+5.50</option><option value='5.75'>+5.75</option><option value='6'>+6.00</option>	    </select>
			  </TD>
			  <TD>
			    <div id="container_od_axis"><select name="od_axis" id="od_axis"><option value="0">0</option></select></div>
			  </TD>
			  <TD>
			    <select name="od_add" id="od_add">
			      <option value='0' selected>0</option><option value='0.25'>0.25</option><option value='0.5'>0.5</option><option value='0.75'>0.75</option><option value='1'>1</option><option value='1.25'>1.25</option><option value='1.5'>1.5</option><option value='1.75'>1.75</option><option value='2'>2</option><option value='2.25'>2.25</option><option value='2.5'>2.5</option><option value='2.75'>2.75</option><option value='3'>3</option>	    </select>
			  </TD>
			</TR>
			<TR>
			  <TD>Left Eye (OS)</TD>
			  <TD>
			    <select name="os_sphere" id="os_sphere" >
			      <option value='-12'>-12.00</option><option value='-11.75'>-11.75</option><option value='-11.5'>-11.50</option><option value='-11.25'>-11.25</option><option value='-11'>-11.00</option><option value='-10.75'>-10.75</option><option value='-10.5'>-10.50</option><option value='-10.25'>-10.25</option><option value='-10'>-10.00</option><option value='-9.75'>-9.75</option><option value='-9.5'>-9.50</option><option value='-9.25'>-9.25</option><option value='-9'>-9.00</option><option value='-8.75'>-8.75</option><option value='-8.5'>-8.50</option><option value='-8.25'>-8.25</option><option value='-8'>-8.00</option><option value='-7.75'>-7.75</option><option value='-7.5'>-7.50</option><option value='-7.25'>-7.25</option><option value='-7'>-7.00</option><option value='-6.75'>-6.75</option><option value='-6.5'>-6.50</option><option value='-6.25'>-6.25</option><option value='-6'>-6.00</option><option value='-5.75'>-5.75</option><option value='-5.5'>-5.50</option><option value='-5.25'>-5.25</option><option value='-5'>-5.00</option><option value='-4.75'>-4.75</option><option value='-4.5'>-4.50</option><option value='-4.25'>-4.25</option><option value='-4'>-4.00</option><option value='-3.75'>-3.75</option><option value='-3.5'>-3.50</option><option value='-3.25'>-3.25</option><option value='-3'>-3.00</option><option value='-2.75'>-2.75</option><option value='-2.5'>-2.50</option><option value='-2.25'>-2.25</option><option value='-2'>-2.00</option><option value='-1.75'>-1.75</option><option value='-1.5'>-1.50</option><option value='-1.25'>-1.25</option><option value='-1'>-1.00</option><option value='-0.75'>-0.75</option><option value='-0.5'>-0.50</option><option value='-0.25'>-0.25</option><option value='0' selected>0.00</option><option value='0.25'>+0.25</option><option value='0.5'>+0.50</option><option value='0.75'>+0.75</option><option value='1'>+1.00</option><option value='1.25'>+1.25</option><option value='1.5'>+1.50</option><option value='1.75'>+1.75</option><option value='2'>+2.00</option><option value='2.25'>+2.25</option><option value='2.5'>+2.50</option><option value='2.75'>+2.75</option><option value='3'>+3.00</option><option value='3.25'>+3.25</option><option value='3.5'>+3.50</option><option value='3.75'>+3.75</option><option value='4'>+4.00</option><option value='4.25'>+4.25</option><option value='4.5'>+4.50</option><option value='4.75'>+4.75</option><option value='5'>+5.00</option><option value='5.25'>+5.25</option><option value='5.5'>+5.50</option><option value='5.75'>+5.75</option><option value='6'>+6.00</option><option value='6.25'>+6.25</option><option value='6.5'>+6.50</option><option value='6.75'>+6.75</option><option value='7'>+7.00</option><option value='7.25'>+7.25</option><option value='7.5'>+7.50</option><option value='7.75'>+7.75</option><option value='8'>+8.00</option><option value='8.25'>+8.25</option><option value='8.5'>+8.50</option><option value='8.75'>+8.75</option><option value='9'>+9.00</option><option value='9.25'>+9.25</option><option value='9.5'>+9.50</option><option value='9.75'>+9.75</option><option value='10'>+10.00</option><option value='10.25'>+10.25</option><option value='10.5'>+10.50</option><option value='10.75'>+10.75</option><option value='11'>+11.00</option><option value='11.25'>+11.25</option><option value='11.5'>+11.50</option><option value='11.75'>+11.75</option><option value='12'>+12.00</option>	    </select>
			  </TD>
			  <TD>
			    <select name="os_cylinder" id="os_cylinder" onchange="ChangeAxis('container_os_axis', 'os_cylinder', 'os_axis');">
		             <option value='-6'>-6.00</option><option value='-5.75'>-5.75</option><option value='-5.5'>-5.50</option><option value='-5.25'>-5.25</option><option value='-5'>-5.00</option><option value='-4.75'>-4.75</option><option value='-4.5'>-4.50</option><option value='-4.25'>-4.25</option><option value='-4'>-4.00</option><option value='-3.75'>-3.75</option><option value='-3.5'>-3.50</option><option value='-3.25'>-3.25</option><option value='-3'>-3.00</option><option value='-2.75'>-2.75</option><option value='-2.5'>-2.50</option><option value='-2.25'>-2.25</option><option value='-2'>-2.00</option><option value='-1.75'>-1.75</option><option value='-1.5'>-1.50</option><option value='-1.25'>-1.25</option><option value='-1'>-1.00</option><option value='-0.75'>-0.75</option><option value='-0.5'>-0.50</option><option value='-0.25'>-0.25</option><option value='0' selected>0.00</option><option value='0.25'>+0.25</option><option value='0.5'>+0.50</option><option value='0.75'>+0.75</option><option value='1'>+1.00</option><option value='1.25'>+1.25</option><option value='1.5'>+1.50</option><option value='1.75'>+1.75</option><option value='2'>+2.00</option><option value='2.25'>+2.25</option><option value='2.5'>+2.50</option><option value='2.75'>+2.75</option><option value='3'>+3.00</option><option value='3.25'>+3.25</option><option value='3.5'>+3.50</option><option value='3.75'>+3.75</option><option value='4'>+4.00</option><option value='4.25'>+4.25</option><option value='4.5'>+4.50</option><option value='4.75'>+4.75</option><option value='5'>+5.00</option><option value='5.25'>+5.25</option><option value='5.5'>+5.50</option><option value='5.75'>+5.75</option><option value='6'>+6.00</option>	    </select>
			  </TD>
			  <TD>
			    <div id="container_os_axis"><select name="os_axis" id="os_axis"><option value="0">0</option></select></div>
			  </TD>
			  <TD>
			    <select name="os_add" id="os_add">
			      <option value='0' selected>0</option><option value='0.25'>0.25</option><option value='0.5'>0.5</option><option value='0.75'>0.75</option><option value='1'>1</option><option value='1.25'>1.25</option><option value='1.5'>1.5</option><option value='1.75'>1.75</option><option value='2'>2</option><option value='2.25'>2.25</option><option value='2.5'>2.5</option><option value='2.75'>2.75</option><option value='3'>3</option>	    </select>
			  </TD>
			</TR>
			<TR style="height:15px"><TD colspan="5"><hr class="rx_hr"></TD></TR>
			<TR>
			  <TD>
			    Pupillary Distance (PD)   <span class="glyphicon glyphicon-question-sign" rel="tooltip" title="<b>PD:</b> Refers to the distance between the eye pupils centers in millimeters. The PD should be a value between 50mm and 74mm, if you received two values for your PD please check the 'I have two values box' to enter them separately. Make sure your two PD values do not exceed 74mm, if so, it is possible that you received two PD values; one for Single Vision reading (the lower of the two) and one for distance (the higher of the two). Unless you wish to use your glasses only for reading, insert the higher value."></span>
			  </TD>
			  <TD colspan=4>
			    <table cellpadding=0 cellspacing=0>
			      <tr>
			        <td style="padding-right:10px;">
				  <div id="pd_1_container">
				  <select name="pd_1" id="pd_1">
				    		    <option value="none" selected>Select</option>
				    <option value='50.00'>50.00</option><option value='50.50'>50.50</option><option value='51.00'>51.00</option><option value='51.50'>51.50</option><option value='52.00'>52.00</option><option value='52.50'>52.50</option><option value='53.00'>53.00</option><option value='53.50'>53.50</option><option value='54.00'>54.00</option><option value='54.50'>54.50</option><option value='55.00'>55.00</option><option value='55.50'>55.50</option><option value='56.00'>56.00</option><option value='56.50'>56.50</option><option value='57.00'>57.00</option><option value='57.50'>57.50</option><option value='58.00'>58.00</option><option value='58.50'>58.50</option><option value='59.00'>59.00</option><option value='59.50'>59.50</option><option value='60.00'>60.00</option><option value='60.50'>60.50</option><option value='61.00'>61.00</option><option value='61.50'>61.50</option><option value='62.00'>62.00</option><option value='62.50'>62.50</option><option value='63.00'>63.00</option><option value='63.50'>63.50</option><option value='64.00'>64.00</option><option value='64.50'>64.50</option><option value='65.00'>65.00</option><option value='65.50'>65.50</option><option value='66.00'>66.00</option><option value='66.50'>66.50</option><option value='67.00'>67.00</option><option value='67.50'>67.50</option><option value='68.00'>68.00</option><option value='68.50'>68.50</option><option value='69.00'>69.00</option><option value='69.50'>69.50</option><option value='70.00'>70.00</option><option value='70.50'>70.50</option><option value='71.00'>71.00</option><option value='71.50'>71.50</option><option value='72.00'>72.00</option><option value='72.50'>72.50</option><option value='73.00'>73.00</option><option value='73.50'>73.50</option><option value='74.00'>74.00</option>		  </select>
				  </div>
				</td>
			        <td>
				  <select name="pd_2" id="pd_2" style="display:none;">
				    		    <option value="none" selected>No PD2</option>
				    <option value='25.00'>25.00</option><option value='25.50'>25.50</option><option value='26.00'>26.00</option><option value='26.50'>26.50</option><option value='27.00'>27.00</option><option value='27.50'>27.50</option><option value='28.00'>28.00</option><option value='28.50'>28.50</option><option value='29.00'>29.00</option><option value='29.50'>29.50</option><option value='30.00'>30.00</option><option value='30.50'>30.50</option><option value='31.00'>31.00</option><option value='31.50'>31.50</option><option value='32.00'>32.00</option><option value='32.50'>32.50</option><option value='33.00'>33.00</option><option value='33.50'>33.50</option><option value='34.00'>34.00</option><option value='34.50'>34.50</option><option value='35.00'>35.00</option><option value='35.50'>35.50</option><option value='36.00'>36.00</option><option value='36.50'>36.50</option><option value='37.00'>37.00</option>		  </select>
				</td>
				<td style="padding-left:10px;">
				  <div class="checkboxes">
				    <label for="chk_pd_2"><input type="checkbox" id="chk_pd_2" onchange="rx.prescription.anotherPD();" /> <span style="font-size:16px">&nbsp;&nbsp;&nbsp;Two PD Numbers</span></label>
				  </div>
				  
				</td>
				<td style"width:200px">
					
				</td>
			      </tr>
			    </table>
			    <div style="clear:left;"></div>
			  </TD>
			</TR>
	</TABLE>
	<div style="margin:20px 0 0 0px;">
		<a href="#" class='rx_link' onclick="showPDInfo(); return false;" >I cannot find my PD?</a>
		<div id='mypd' style="display:none; position:absolute; left:50px;top:20px; width:90%;height:90%;background:#FFF; border: 1px solid #333333; z-index: 3;overflow:scroll" >
			
		</div>
	</div>
	<div style="margin:20px 0 0 0px;">
		<label for="prism"><input type="checkbox" id="prism" name="prism" onchange="rx.prescription.showPrism();"/> <span style="font-size:16px;">&nbsp;&nbsp;&nbsp;Add Prism </span></label>   <span class="glyphicon glyphicon-question-sign" rel="tooltip" title="<b>Prism:</b> Refers to an extra feature that in some cases needed to help the eyes stay in proper alignment."></span>
	</div>
	<div style="clear:left;"></div>
	<div id="container_prism" style="display:none;">
		<TABLE class="rx_table">
			<TR>
			  <TD style="width: 28%"></TD>
			  <TD style="width: 18%">
			    Vertical Prism   <span class="glyphicon glyphicon-question-sign" rel="tooltip" title="<b>Vertical Prism:</b> Prescribed in opposite directions, it meant to correct vertical eye misalignment."></span>
			  </TD>
			  <TD style="width: 18%">
			    Base Direction   <span class="glyphicon glyphicon-question-sign" rel="tooltip" title="<b>Base Direction:</b> The prism base direction"></span>
			  </TD>
			  <TD style="width: 18%">
			    Horizontal Prism   <span class="glyphicon glyphicon-question-sign" rel="tooltip" title="<b>Horizontal Prism:</b> prescribed in the same direction on both eyes, it meant to correct lateral eye misalignment"></span>
			  </TD>
			  <TD style="width: 18%">
			    Base Direction   <span class="glyphicon glyphicon-question-sign" rel="tooltip" title="<b>Base Direction:</b> The prism base direction"></span>
			  </TD>
			</TR>
			<TR style="height:15px"><TD colspan="5"><hr class="rx_hr"></TD></TR>
			<TR>
			  <TD>Right Eye (OD)</TD>
			  <TD>
			    <select name="od_vp" id="od_vp">
			      <option value='0' selected>0</option><option value='0.25'>0.25</option><option value='0.5'>0.5</option><option value='0.75'>0.75</option><option value='1'>1</option><option value='1.25'>1.25</option><option value='1.5'>1.5</option><option value='1.75'>1.75</option><option value='2'>2</option><option value='2.25'>2.25</option><option value='2.5'>2.5</option><option value='2.75'>2.75</option><option value='3'>3</option><option value='3.25'>3.25</option><option value='3.5'>3.5</option><option value='3.75'>3.75</option><option value='4'>4</option><option value='4.25'>4.25</option><option value='4.5'>4.5</option><option value='4.75'>4.75</option><option value='5'>5</option><option value='5.25'>5.25</option><option value='5.5'>5.5</option><option value='5.75'>5.75</option><option value='6'>6</option><option value='6.25'>6.25</option><option value='6.5'>6.5</option><option value='6.75'>6.75</option><option value='7'>7</option>	    </select>
			  </TD>
			  <TD>
			    <select name="od_vp_basedirection" id="od_vp_basedirection">
			      <option value='n/a' selected>n/a</option>
			      <option value='in'>Up</option>
			      <option value='out'>Down</option>
			    </select>
			  </TD>
			  <TD>
			    <select name="od_hp" id="od_hp">
			      <option value='0' selected>0</option><option value='0.25'>0.25</option><option value='0.5'>0.5</option><option value='0.75'>0.75</option><option value='1'>1</option><option value='1.25'>1.25</option><option value='1.5'>1.5</option><option value='1.75'>1.75</option><option value='2'>2</option><option value='2.25'>2.25</option><option value='2.5'>2.5</option><option value='2.75'>2.75</option><option value='3'>3</option><option value='3.25'>3.25</option><option value='3.5'>3.5</option><option value='3.75'>3.75</option><option value='4'>4</option><option value='4.25'>4.25</option><option value='4.5'>4.5</option><option value='4.75'>4.75</option><option value='5'>5</option><option value='5.25'>5.25</option><option value='5.5'>5.5</option><option value='5.75'>5.75</option><option value='6'>6</option><option value='6.25'>6.25</option><option value='6.5'>6.5</option><option value='6.75'>6.75</option><option value='7'>7</option>	    </select>
			  </TD>
			  <TD>
			    <select name="od_hp_basedirection" id="od_hp_basedirection">
			      <option value='n/a' selected>n/a</option>
			      <option value='in'>In</option>
			      <option value='out'>Out</option>
			    </select>
			  </TD>
			</TR>
			<TR>
			  <TD>Left Eye (OS)</TD>
			  <TD>
			    <select name="os_vp" id="os_vp">
			      <option value='0' selected>0</option><option value='0.25'>0.25</option><option value='0.5'>0.5</option><option value='0.75'>0.75</option><option value='1'>1</option><option value='1.25'>1.25</option><option value='1.5'>1.5</option><option value='1.75'>1.75</option><option value='2'>2</option><option value='2.25'>2.25</option><option value='2.5'>2.5</option><option value='2.75'>2.75</option><option value='3'>3</option><option value='3.25'>3.25</option><option value='3.5'>3.5</option><option value='3.75'>3.75</option><option value='4'>4</option><option value='4.25'>4.25</option><option value='4.5'>4.5</option><option value='4.75'>4.75</option><option value='5'>5</option><option value='5.25'>5.25</option><option value='5.5'>5.5</option><option value='5.75'>5.75</option><option value='6'>6</option><option value='6.25'>6.25</option><option value='6.5'>6.5</option><option value='6.75'>6.75</option><option value='7'>7</option>	    </select>
			  </TD>
			  <TD>
			    <select name="os_vp_basedirection" id="os_vp_basedirection">
			      <option value='n/a' selected>n/a</option>
			      <option value='in'>Up</option>
			      <option value='out'>Down</option>
			    </select>
			  </TD>
			  <TD>
			    <select name="os_hp" id="os_hp">
			      <option value='0' selected>0</option><option value='0.25'>0.25</option><option value='0.5'>0.5</option><option value='0.75'>0.75</option><option value='1'>1</option><option value='1.25'>1.25</option><option value='1.5'>1.5</option><option value='1.75'>1.75</option><option value='2'>2</option><option value='2.25'>2.25</option><option value='2.5'>2.5</option><option value='2.75'>2.75</option><option value='3'>3</option><option value='3.25'>3.25</option><option value='3.5'>3.5</option><option value='3.75'>3.75</option><option value='4'>4</option><option value='4.25'>4.25</option><option value='4.5'>4.5</option><option value='4.75'>4.75</option><option value='5'>5</option><option value='5.25'>5.25</option><option value='5.5'>5.5</option><option value='5.75'>5.75</option><option value='6'>6</option><option value='6.25'>6.25</option><option value='6.5'>6.5</option><option value='6.75'>6.75</option><option value='7'>7</option>	    </select>
			  </TD>
			  <TD>
			    <select name="os_hp_basedirection" id="os_hp_basedirection">
			      <option value='n/a' selected>n/a</option>
			      <option value='in'>In</option>
			      <option value='out'>Out</option>
			    </select>
			  </TD>
			</TR>
			<TR style="height:15px"><TD colspan="5"><hr class="rx_hr"></TD></TR>
		 </TABLE>
	</div>		      
</div>
