<?php
require_once('fpdf/fpdf.php');
require_once('vendor/setasign/fpdi/src/autoload.php');
if($courier_id =='1' || $courier_id =='5' ){
    if(!empty($label_arra)){
        if(count($label_arra) >1){
            for($i=0;$i<count($label_arra);$i++){
                if($courier_id =='5'){
                    $decoded = base64_decode($label_arra[$i]['label'],true);
                }else{
                    $decoded = ($label_arra[$i]['label']);
                }
                file_put_contents($label_arra[$i]['awb'].'.pdf', $decoded);
            }
            $merger = new \setasign\Fpdi\Fpdi('L');
            
            for($j=0;$j<count($label_arra);$j++){
                $pageCount = $merger->setSourceFile($label_arra[$j]['awb'].'.pdf');
                for ($i = 1; $i <= $pageCount; $i++) {
                    $tplId = $merger->importPage($i);
                    $merger->addPage();
                    $merger->useTemplate($tplId);
                }
            }
            

            // Output the merged PDF to a file
            $merger->Output('shipping_'.$id.'.pdf', 'F');
            $file = 'shipping_'.$id.'.pdf';
            // file_put_contents($file, $decoded);

            if (file_exists($file)) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="'.basename($file).'"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file));
                readfile($file);
            }
            for($i=0;$i<count($label_arra);$i++){
                File::delete($label_arra[$i]['awb'].'.pdf');
            }
            // File::delete($file);
        }else{
            if($courier_id =='5'){
                $decoded = base64_decode($label_arra[0]['label'],true);
            }else{
                $decoded = ($label_arra[0]['label']);
            }
            $file = 'shipping_'.$id.'.pdf';
            file_put_contents($file, $decoded);
            if (file_exists($file)) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="'.basename($file).'"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file));
                readfile($file);
            }
        }
        exit;

    }else{
        echo 'Something went wrong, please contact admin1';
    }
    exit;
}else if($courier_id =='6'){
    if(!empty($label_arra)){
        if(count($label_arra) >1){
            for($i=0;$i<count($label_arra);$i++){
                $filed =@file_get_contents($label_arra[$i]['pdf']);
                if($filed === false){
                    echo 'We are generating your file, please try after 10min';
                    exit;
                }
                $decoded = base64_decode(chunk_split(base64_encode($filed)),true);
                file_put_contents($label_arra[$i]['awb'].'.pdf', $decoded);
            }
            $merger = new \setasign\Fpdi\Fpdi('L');
                    
            for($j=0;$j<count($label_arra);$j++){
                $pageCount = $merger->setSourceFile($label_arra[$j]['awb'].'.pdf');
                for ($i = 1; $i <= $pageCount; $i++) {
                    $tplId = $merger->importPage($i);
                    $merger->addPage();
                    $merger->useTemplate($tplId);
                }
            }
            

            // Output the merged PDF to a file
            $merger->Output('shipping_'.$id.'.pdf', 'F');
            $file = 'shipping_'.$id.'.pdf';
            // file_put_contents($file, $decoded);

            if (file_exists($file)) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="'.basename($file).'"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file));
                readfile($file);
            }
            for($i=0;$i<count($label_arra);$i++){
                File::delete($label_arra[$i]['awb'].'.pdf');
            }
            // File::delete($file);
        }else{
            $filed = @file_get_contents($label_arra[0]['pdf']);
            // echo $filed;die;
            if($filed === false){
                echo 'We are generating your file, please try after 10min';
                exit;
            }
            $decoded = base64_decode(chunk_split(base64_encode($filed)),true);
            $file = 'shipping_'.$id.'.pdf';
            file_put_contents($file, $decoded);
            if (file_exists($file)) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="'.basename($file).'"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file));
                readfile($file);
            }
        }    
    }else{
        echo 'Something went wrong, please contact admin2';
    }    
    exit;
}else if($courier_id =='4' || $courier_id =='2'){
    if(!empty($label_arra)){
        // Create instance of FPDF with custom size 4x6 inches (101.6 x 152.4 mm)
        $pdf = new \setasign\Fpdi\Fpdi('P', 'mm', array(101.6, 152.4));
        
        $couriers_json = json_decode(@file_get_contents(resource_path('views/admin/courier.json')), true) ?: [];
        $courier_name = 'SHIPMENT';
        if (isset($couriers_json[$courier_id])) {
            $courier_name = strtoupper($couriers_json[$courier_id]['name']);
        }
        
        foreach($label_arra as $key => $order){
            $pdf->AddPage();
            $pdf->SetAutoPageBreak(false);
            
            // Draw outer border
            $pdf->Rect(2, 2, 97.6, 148.4);
            
            // 1. Courier Header
            $pdf->SetFont('Arial', 'B', 14);
            $pdf->SetXY(4, 5);
            $pdf->Cell(93.6, 6, $courier_name, 0, 1, 'L');
            
            // Draw Logo if exists
            $admin = auth()->guard('admin')->user();
            $ls = \App\Models\LabelSetting::where('user_id', $admin->id)->first();
            $general_setting = \DB::table('general_settings')->where('company_id', $admin->company_id)->first();
            $logo_path = ($ls && $ls->logo) ? $ls->logo : (isset($general_setting->logo) ? $general_setting->logo : null);
            if ($logo_path && !($ls && $ls->logo_hidden)) {
                $full_logo_path = public_path('uploads/' . $logo_path);
                if (file_exists($full_logo_path)) {
                    $pdf->Image($full_logo_path, 60, 4, 35, 10);
                }
            }
            
            // Line separator
            $pdf->Line(2, 16, 99.6, 16);
            
            // 2. AWB & Barcode
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->SetXY(4, 18);
            $pdf->Cell(93.6, 4, 'AWB: ' . ($order->tracking_info ?? 'N/A'), 0, 1, 'C');
            
            // Generate and draw barcode image
            if ($order->tracking_info) {
                require_once base_path('vendor/picqer/php-barcode-generator/src/BarcodeBar.php');
                require_once base_path('vendor/picqer/php-barcode-generator/src/Barcode.php');
                require_once base_path('vendor/picqer/php-barcode-generator/src/Exceptions/BarcodeException.php');
                require_once base_path('vendor/picqer/php-barcode-generator/src/Exceptions/UnknownTypeException.php');
                require_once base_path('vendor/picqer/php-barcode-generator/src/Types/TypeInterface.php');
                require_once base_path('vendor/picqer/php-barcode-generator/src/BarcodeGenerator.php');
                require_once base_path('vendor/picqer/php-barcode-generator/src/Types/TypeCode128.php');
                require_once base_path('vendor/picqer/php-barcode-generator/src/BarcodeGeneratorPNG.php');
                
                try {
                    $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
                    $barcode_png = $generator->getBarcode($order->tracking_info, $generator::TYPE_CODE_128, 2, 45);
                    $temp_barcode_path = storage_path('app/temp_barcode_' . $order->id . '.png');
                    file_put_contents($temp_barcode_path, $barcode_png);
                    if (file_exists($temp_barcode_path)) {
                        $pdf->Image($temp_barcode_path, 15, 23, 71.6, 12, 'PNG');
                        unlink($temp_barcode_path);
                    }
                } catch (\Exception $e) {
                    // barcode failed, print placeholder
                    $pdf->SetXY(4, 25);
                    $pdf->Cell(93.6, 4, '[Barcode Generation Error]', 0, 1, 'C');
                }
            }
            
            // Line separator
            $pdf->Line(2, 38, 99.6, 38);
            
            // 3. Sender / Pickup Address (Left) & Return / RTO Address (Right)
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetXY(4, 40);
            $pdf->Cell(45, 4, 'Pickup Address:', 0, 0, 'L');
            $pdf->Cell(45, 4, 'RTO Address:', 0, 1, 'L');
            
            $w = $order->warehouse_id ? \App\Models\Admin\Warehouse::find($order->warehouse_id) : null;
            if (!$w) {
                $w = \App\Models\Admin\Warehouse::where('company_id', $order->company_id)->first() ?? \App\Models\Admin\Warehouse::first();
            }
            $rw = $order->return_warehouse_id ? \App\Models\Admin\Warehouse::find($order->return_warehouse_id) : $w;
            if (!$rw) {
                $rw = $w;
            }
            
            $pdf->SetFont('Arial', '', 7);
            
            // Left address multi-cell
            $pickup_addr = ($w ? ($w->name . "\n" . $w->address . "\n" . $w->city . " - " . $w->pincode . "\nPh: " . $w->mobile) : 'N/A');
            // Right address multi-cell
            $rto_addr = ($rw ? ($rw->name . "\n" . $rw->address . "\n" . $rw->city . " - " . $rw->pincode . "\nPh: " . $rw->mobile) : 'N/A');
            
            $pdf->SetXY(4, 44);
            $pdf->MultiCell(44, 3, $pickup_addr, 0, 'L');
            
            $pdf->SetXY(52, 44);
            $pdf->MultiCell(44, 3, $rto_addr, 0, 'L');
            
            // Line separator
            $pdf->Line(2, 65, 99.6, 65);
            
            // 4. Shipping Address (Consignee)
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->SetXY(4, 67);
            $pdf->Cell(93.6, 4, 'Ship To:', 0, 1, 'L');
            
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->SetX(4);
            $pdf->Cell(93.6, 4, $order->ship_fname . ' ' . $order->ship_lname, 0, 1, 'L');
            
            $pdf->SetFont('Arial', '', 9);
            $pdf->SetX(4);
            $ship_address = $order->ship_address . ' ' . $order->ship_address_2 . "\n" . $order->ship_city . ", " . $order->ship_state . " - " . $order->ship_pincode;
            $pdf->MultiCell(93.6, 4, $ship_address, 0, 'L');
            
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->SetX(4);
            $pdf->Cell(93.6, 5, 'Phone: ' . $order->ship_phone, 0, 1, 'L');
            
            // Line separator
            $pdf->Line(2, 98, 99.6, 98);
            
            // 5. Payment details and Order info
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->SetXY(4, 100);
            $pm = (strip_tags($order->payment_mode) == 'C.O.D' || strip_tags($order->payment_mode) == 'COD') ? 'COD' : 'PrePaid';
            $pdf->Cell(45, 5, 'Payment: ' . strtoupper($pm), 0, 0, 'L');
            $pdf->SetFont('Arial', 'B', 11);
            $pdf->Cell(45, 5, 'Amount: Rs. ' . number_format($order->total, 2), 0, 1, 'R');
            
            // Table for products
            $pdf->SetXY(4, 107);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(63.6, 4, 'Product Name', 1, 0, 'L');
            $pdf->Cell(15, 4, 'Qty', 1, 0, 'C');
            $pdf->Cell(15, 4, 'Price', 1, 1, 'C');
            
            $pdf->SetFont('Arial', '', 7);
            $details_to_print = $order->detail->take(3);
            foreach($details_to_print as $item){
                $pdf->SetX(4);
                $pdf->Cell(63.6, 4, substr($item->name, 0, 42), 1, 0, 'L');
                $pdf->Cell(15, 4, $item->qty, 1, 0, 'C');
                $pdf->Cell(15, 4, number_format($item->price, 2), 1, 1, 'C');
            }
            if ($order->detail->count() > 3) {
                $pdf->SetX(4);
                $pdf->Cell(93.6, 4, '... and ' . ($order->detail->count() - 3) . ' more item(s)', 1, 1, 'C');
            }
            
            // Dimensions / Weight info at the bottom
            $pdf->SetXY(4, 138);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(93.6, 4, 'Weight: ' . number_format($order->weight / 1000, 2) . ' kg | Vol: ' . $order->length . 'x' . $order->breadth . 'x' . $order->height . ' cm', 0, 1, 'L');
            
            $pdf->SetFont('Arial', 'I', 7);
            $pdf->SetX(4);
            $pdf->Cell(93.6, 4, 'Thank you for shipping with Hyloship!', 0, 1, 'C');
        }
        
        // Output the PDF
        if (!file_exists('shipping_label')) {
            mkdir('shipping_label', 0777, true);
        }
        $pdf->Output('shipping_label/'.$name.''.$id.'.pdf', 'F');
        $file = 'shipping_label/'.$name.''.$id.'.pdf';
        
        if (file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($file).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            readfile($file);
        }
        exit;
    } else {
        echo 'Something went wrong, please contact admin4';
    }
    exit;
}else if($courier_id =='9'){
    if(!empty($label_arra)){
        for($i=0;$i<count($label_arra);$i++){
            $decoded = base64_decode($label_arra[$i]['awb']);
            file_put_contents('image'.$i.''.$courier_id.'.'.$label_arra[$i]['format'], $decoded);
        }
        // Create instance of FPDF
        $pdf = new \setasign\Fpdi\Fpdi();

        // Add a page
        $pdf->AddPage();
        
        // Set initial position
        $x = 30; // Initial x-coordinate
        $y = 10; // Initial y-coordinate

        // Loop through the image paths and add images to the PDF
        for($j=0;$j<count($label_arra);$j++){
            $imagePath = 'image'.$j.''.$courier_id.'.'.$label_arra[$j]['format'];
            // Add image to the PDF
            if (file_exists($imagePath)) {
                // Determine image dimensions
                list($width, $height) = getimagesize($imagePath);
                
                // Calculate aspect ratio to maintain proportions
                $ratio = $width / $height;
                // Calculate new width and height to fit within the page
                $maxWidth = 280; // Maximum width for the image
                $maxHeight = 220; // Maximum height for the image
                $newWidth = min($maxWidth, $width);
                $newHeight = $newWidth / $ratio;
                if ($newHeight > $maxHeight) {
                    $newHeight = $maxHeight;
                    $newWidth = $newHeight * $ratio;
                }
                // Check if adding this image will exceed the page height
                if ($y + $newHeight > $pdf->GetPageHeight()) {
                    // If yes, start a new page
                    $pdf->AddPage();
                    // Reset y-coordinate to top of the page
                    $y = 10;
                }

                // Add image to the PDF
                $pdf->Image($imagePath, $x, $y, $newWidth, $newHeight); // (path, x, y, width, height)

                // Update y-coordinate for the next image
                $y += $newHeight + 10; // Add some spacing between images

            }
            
        }               
        
        // Output the PDF to the browser or save it to a file
        if (!file_exists('shipping_label')) {
            mkdir('shipping_label', 0777, true);
        }
        $pdf->Output('shipping_label/'.$name.''.$id.'.pdf', 'F'); // 'F' to save the PDF to a file, 'I' to output to the browser
        $file = 'shipping_label/'.$name.''.$id.'.pdf';
        
        if (file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($file).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            readfile($file);
        }
        for($i=0;$i<count($label_arra);$i++){
            File::delete('image'.$i.''.$courier_id.'.'.$label_arra[$i]['format']);
        }
        // File::delete($file);
            
    }else{
        echo 'Something went wrong, please contact admin2';
    }    
    exit;
}else{
    // Universal fallback for any other courier to completely eliminate admin3 errors
    if(!empty($label_arra)){
        // Create instance of FPDF with custom size 4x6 inches (101.6 x 152.4 mm)
        $pdf = new \setasign\Fpdi\Fpdi('P', 'mm', array(101.6, 152.4));
        
        $couriers_json = json_decode(@file_get_contents(resource_path('views/admin/courier.json')), true) ?: [];
        $courier_name = 'SHIPMENT';
        if (isset($couriers_json[$courier_id])) {
            $courier_name = strtoupper($couriers_json[$courier_id]['name']);
        }
        
        foreach($label_arra as $key => $order){
            $pdf->AddPage();
            $pdf->SetAutoPageBreak(false);
            
            // Draw outer border
            $pdf->Rect(2, 2, 97.6, 148.4);
            
            // 1. Courier Header
            $pdf->SetFont('Arial', 'B', 14);
            $pdf->SetXY(4, 5);
            $pdf->Cell(93.6, 6, $courier_name, 0, 1, 'L');
            
            // Draw Logo if exists
            $admin = auth()->guard('admin')->user();
            $ls = \App\Models\LabelSetting::where('user_id', $admin->id)->first();
            $general_setting = \DB::table('general_settings')->where('company_id', $admin->company_id)->first();
            $logo_path = ($ls && $ls->logo) ? $ls->logo : (isset($general_setting->logo) ? $general_setting->logo : null);
            if ($logo_path && !($ls && $ls->logo_hidden)) {
                $full_logo_path = public_path('uploads/' . $logo_path);
                if (file_exists($full_logo_path)) {
                    $pdf->Image($full_logo_path, 60, 4, 35, 10);
                }
            }
            
            // Line separator
            $pdf->Line(2, 16, 99.6, 16);
            
            // 2. AWB & Barcode
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->SetXY(4, 18);
            $pdf->Cell(93.6, 4, 'AWB: ' . ($order->tracking_info ?? 'N/A'), 0, 1, 'C');
            
            // Generate and draw barcode image
            if ($order->tracking_info) {
                require_once base_path('vendor/picqer/php-barcode-generator/src/BarcodeBar.php');
                require_once base_path('vendor/picqer/php-barcode-generator/src/Barcode.php');
                require_once base_path('vendor/picqer/php-barcode-generator/src/Exceptions/BarcodeException.php');
                require_once base_path('vendor/picqer/php-barcode-generator/src/Exceptions/UnknownTypeException.php');
                require_once base_path('vendor/picqer/php-barcode-generator/src/Types/TypeInterface.php');
                require_once base_path('vendor/picqer/php-barcode-generator/src/BarcodeGenerator.php');
                require_once base_path('vendor/picqer/php-barcode-generator/src/Types/TypeCode128.php');
                require_once base_path('vendor/picqer/php-barcode-generator/src/BarcodeGeneratorPNG.php');
                
                try {
                    $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
                    $barcode_png = $generator->getBarcode($order->tracking_info, $generator::TYPE_CODE_128, 2, 45);
                    $temp_barcode_path = storage_path('app/temp_barcode_' . $order->id . '.png');
                    file_put_contents($temp_barcode_path, $barcode_png);
                    if (file_exists($temp_barcode_path)) {
                        $pdf->Image($temp_barcode_path, 15, 23, 71.6, 12, 'PNG');
                        unlink($temp_barcode_path);
                    }
                } catch (\Exception $e) {
                    $pdf->SetXY(4, 25);
                    $pdf->Cell(93.6, 4, '[Barcode Generation Error]', 0, 1, 'C');
                }
            }
            
            // Line separator
            $pdf->Line(2, 38, 99.6, 38);
            
            // 3. Sender / Pickup Address (Left) & Return / RTO Address (Right)
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetXY(4, 40);
            $pdf->Cell(45, 4, 'Pickup Address:', 0, 0, 'L');
            $pdf->Cell(45, 4, 'RTO Address:', 0, 1, 'L');
            
            $w = $order->warehouse_id ? \App\Models\Admin\Warehouse::find($order->warehouse_id) : null;
            if (!$w) {
                $w = \App\Models\Admin\Warehouse::where('company_id', $order->company_id)->first() ?? \App\Models\Admin\Warehouse::first();
            }
            $rw = $order->return_warehouse_id ? \App\Models\Admin\Warehouse::find($order->return_warehouse_id) : $w;
            if (!$rw) {
                $rw = $w;
            }
            
            $pdf->SetFont('Arial', '', 7);
            
            // Left address multi-cell
            $pickup_addr = ($w ? ($w->name . "\n" . $w->address . "\n" . $w->city . " - " . $w->pincode . "\nPh: " . $w->mobile) : 'N/A');
            // Right address multi-cell
            $rto_addr = ($rw ? ($rw->name . "\n" . $rw->address . "\n" . $rw->city . " - " . $rw->pincode . "\nPh: " . $rw->mobile) : 'N/A');
            
            $pdf->SetXY(4, 44);
            $pdf->MultiCell(44, 3, $pickup_addr, 0, 'L');
            
            $pdf->SetXY(52, 44);
            $pdf->MultiCell(44, 3, $rto_addr, 0, 'L');
            
            // Line separator
            $pdf->Line(2, 65, 99.6, 65);
            
            // 4. Shipping Address (Consignee)
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->SetXY(4, 67);
            $pdf->Cell(93.6, 4, 'Ship To:', 0, 1, 'L');
            
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->SetX(4);
            $pdf->Cell(93.6, 4, $order->ship_fname . ' ' . $order->ship_lname, 0, 1, 'L');
            
            $pdf->SetFont('Arial', '', 9);
            $pdf->SetX(4);
            $ship_address = $order->ship_address . ' ' . $order->ship_address_2 . "\n" . $order->ship_city . ", " . $order->ship_state . " - " . $order->ship_pincode;
            $pdf->MultiCell(93.6, 4, $ship_address, 0, 'L');
            
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->SetX(4);
            $pdf->Cell(93.6, 5, 'Phone: ' . $order->ship_phone, 0, 1, 'L');
            
            // Line separator
            $pdf->Line(2, 98, 99.6, 98);
            
            // 5. Payment details and Order info
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->SetXY(4, 100);
            $pm = (strip_tags($order->payment_mode) == 'C.O.D' || strip_tags($order->payment_mode) == 'COD') ? 'COD' : 'PrePaid';
            $pdf->Cell(45, 5, 'Payment: ' . strtoupper($pm), 0, 0, 'L');
            $pdf->SetFont('Arial', 'B', 11);
            $pdf->Cell(45, 5, 'Amount: Rs. ' . number_format($order->total, 2), 0, 1, 'R');
            
            // Table for products
            $pdf->SetXY(4, 107);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(63.6, 4, 'Product Name', 1, 0, 'L');
            $pdf->Cell(15, 4, 'Qty', 1, 0, 'C');
            $pdf->Cell(15, 4, 'Price', 1, 1, 'C');
            
            $pdf->SetFont('Arial', '', 7);
            $details_to_print = $order->detail->take(3);
            foreach($details_to_print as $item){
                $pdf->SetX(4);
                $pdf->Cell(63.6, 4, substr($item->name, 0, 42), 1, 0, 'L');
                $pdf->Cell(15, 4, $item->qty, 1, 0, 'C');
                $pdf->Cell(15, 4, number_format($item->price, 2), 1, 1, 'C');
            }
            if ($order->detail->count() > 3) {
                $pdf->SetX(4);
                $pdf->Cell(93.6, 4, '... and ' . ($order->detail->count() - 3) . ' more item(s)', 1, 1, 'C');
            }
            
            // Dimensions / Weight info at the bottom
            $pdf->SetXY(4, 138);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(93.6, 4, 'Weight: ' . number_format($order->weight / 1000, 2) . ' kg | Vol: ' . $order->length . 'x' . $order->breadth . 'x' . $order->height . ' cm', 0, 1, 'L');
            
            $pdf->SetFont('Arial', 'I', 7);
            $pdf->SetX(4);
            $pdf->Cell(93.6, 4, 'Thank you for shipping with Hyloship!', 0, 1, 'C');
        }
        
        // Output the PDF
        if (!file_exists('shipping_label')) {
            mkdir('shipping_label', 0777, true);
        }
        $pdf->Output('shipping_label/'.$name.''.$id.'.pdf', 'F');
        $file = 'shipping_label/'.$name.''.$id.'.pdf';
        
        if (file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($file).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            readfile($file);
        }
        exit;
    } else {
        echo 'Something went wrong, please contact admin3';
    }
    exit;
}
?>

