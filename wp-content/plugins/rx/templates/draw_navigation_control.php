<?php

if ($d_mode != 'fashion') {
    draw_navbar("nb_step1", "<< Frame", "rx.navigation.hideRx()", "Prescription >>", "rx.step.set(1)"); //step 1 
    draw_navbar("nb_step1_progressive", "<< Frame", "rx.navigation.hideRx()", "Next >>", "rx.step.set(150)");  // step 1 alternative menu for progressive 
	
    draw_navbar("nb_step150", "<< Usage", "rx.navigation.reset(1)", "Prescription >>", "rx.step.set(1)"); // step 150 	
    draw_navbar("nb_step150_office", "<< Usage", "rx.navigation.reset(1)", "Distance >>", "rx.step.set(170)"); // step 150 alternative menu for office

	draw_navbar("nb_step170", "<< Back", "rx.navigation.reset(150,170)", "Prescription >>", "rx.step.set(1)");  // step 170 

    draw_navbar("nb_step2", "<< Usage", "rx.navigation.reset(1)", "Lens Package >>", "rx.step.set(2)"); //step 2
	
    draw_navbar("nb_step2_progressive", "<< Back", "rx.navigation.reset(150)", "Lens Package >>", "rx.step.set(2)");  // back to step 150 if is in that flow  //ok ok i know is duplicate menu for every flow branch but is made like this
    draw_navbar("nb_step2_distance", "<< Distance", "rx.navigation.reset(170)", "Lens Package >>", "rx.step.set(2)");  // back to step 170 if is in that flow 
    
	draw_navbar("nb_step2_progressive_premium", "<< Back", "rx.navigation.reset(150)", "Lens Type >>", "rx.step.set(250)");  // back to step 150 if is in that flow && and forward to 250  
	
	draw_navbar("nb_step2_landingProgressive", "<< Progressive Type", "location.reload();", "Lens Package >>", "rx.step.set(2)");  // back to step 150 if is in that flow && and forward to 2  
	draw_navbar("nb_step2_landingProgressive_premium", "<< Progressive Type", "location.reload();", "Lens Type >>", "rx.step.set(250)");  // back to step 150 if is in that flow && and forward to 250  
	
	draw_navbar("nb_step250", "<< Prescription", "rx.navigation.reset(2)", "Lens Package >>", "rx.step.set(2)"); // step 250 
	
    draw_navbar("nb_step3", "<< Prescription", "rx.navigation.reset(2)", "Lens Package >>", "rx.step.set(3)"); // step 3 
	
    draw_navbar("nb_step4", "<< Lens Color", "rx.navigation.reset(3)", "Coating Options >>", "rx.step.set(4)");
    draw_navbar("nb_step5", "<< Lens Package", "rx.navigation.reset(4)", "Review Order >>", "rx.previewOrder.reviewOrder('custom')");
    
	draw_navbar("nb_step6", "<< Edit Lenses", "rx.navigation.reset(5)", "Add to Cart >>", "ToCart()");
	draw_navbar("nb_step6_landingProgressive", "<< Edit Lenses", "rx.navigation.reset(5)", "Add to Cart >>", "ToCart()");
	
    draw_navbar("package_step6", "<< Prescription", "rx.navigation.reset(7)", "Review Order >>", "rx.package.reviewPreset()");	//difference between this 2 ?? both show in step7 
    
	draw_navbar("nb_step7", "<< Prescription", "rx.navigation.reset(7)", "Review Order >>", "rx.package.reviewPreset()");			//difference between this 2 ?? both show in step7
	draw_navbar("nb_step7_premium", "<< Lens Type", "rx.navigation.reset(250)", "Review Order >>", "rx.package.reviewPreset()"); // back to step 250 if is in that flow 
    
	draw_navbar("nb_step8", "<< Edit Lenses", "rx.navigation.reset(8)", "Add to Cart", "ToCart()");
} else {
    draw_navbar("nb_step3", "<< Frame", "rx.navigation.hideRx()", "Review Order >>", "rx.previewOrder.reviewOrder('custom')");
    draw_navbar("nb_step6", "<< Edit Lenses", "rx.fashion.reset()", "Add to Cart >>", "ToCart()");
}
