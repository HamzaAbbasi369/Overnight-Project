

<!--
<h1 style="text-align: left; font-size: 20px; margin-bottom: 20px; margin-left: 20px;" >Order Prescription Lenses</h1>
-->
<?php
  if($d_mode!='fashion') :
?>
<div class="nav-progress" style="display:none">
 <div class="nav-cap"></div>
  <div id="progress-num-1" class="numberCircle circleActive">
    1
  </div>
  <div class="nav-progress-spacer">
    <div id="progress-label-1" class="nav-step-lbl nav-progress-active">Usage</div> 
    <div id="progress-line-2" class="nav-progress-line nav-progress-line-passive"></div> 
  </div>
  <div  id="progress-num-2" class="numberCircle circlePassive">
    2
  </div>
  <div class="nav-progress-spacer">
    <div id="progress-label-2" class="nav-step-lbl nav-progress-passive">Prescription</div> 
    <div id="progress-line-3" class="nav-progress-line nav-progress-line-passive"></div> 
  </div>
  <div  id="progress-num-3" class="numberCircle circlePassive">
    3
  </div>
  <div class="nav-progress-spacer">
    <div id="progress-label-3" class="nav-step-lbl nav-progress-passive">Lens Color</div> 
    <div id="progress-line-4" class="nav-progress-line nav-progress-line-passive"></div> 
  </div>

  <div  id="progress-num-4" class="numberCircle circlePassive">
    4
  </div>
  <div class="nav-progress-spacer">
    <div id="progress-label-4" class="nav-step-lbl nav-progress-passive">Package</div> 
    <div id="progress-line-5" class="nav-progress-line nav-progress-line-passive"></div> 
  </div>
  <div  id="progress-num-5" class="numberCircle circlePassive">
    5
  </div>
  <div class="nav-cap">
    <div id="progress-label-5" class="nav-step-lbl nav-progress-passive">Coating</div>
  </div>
</div>
<!--<h1 id="progress-step-name"style="text-align: center; font-size: 15px; margin-bottom: 20px; margin-top: 15px;">SELECT A LENS TYPE</h1>-->
      <h1 class="rx-product-header-name" id="progress-step-name">I Need Glasses For</h1>
<?php else: ?>
<!--<h1 id="progress-step-name"style="text-align: center; font-size: 15px; margin-bottom: 20px; margin-top: 15px;">SELECT A TINT TYPE</h1>-->
      <h1 class="rx-product-header-name" id="progress-step-name">SELECT A TINT TYPE</h1>
<?php endif; ?>
