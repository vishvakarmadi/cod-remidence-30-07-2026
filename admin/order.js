"use strict";
    (function($) { 
        $(document).on('input change', '.tax_percent', function() {
            var row = $(this).closest('tr');
            var qty = parseFloat(row.find('input[name="qty[]"]').val()) || 0;
            var price = parseFloat(row.find('input[name="price[]"]').val()) || 0;
            var discount = parseFloat(row.find('input[name="discount[]"]').val()) || 0;
            var type = row.find('select[name="discount_type[]"]').val();
            var tax_id = parseFloat(row.find('input[name="tax_percent[]"]').val()) || 0;

            if(tax_id > 0 && qty > 0 && price>0){
                var gst = tax_id / 100;
                var act_cost = price * qty;
                var gstrate = qty * price * gst;
                row.find("input[name='tax_amount[]']").val(gstrate.toFixed(2));
                var dis_amt = 0;
                if (type === 'p') {
                    dis_amt = ((price * qty * discount) / 100);
                } else if (type === 'f') {
                    dis_amt = discount;
                }
                row.find("input[name='total_price[]']").val(((act_cost + gstrate)-dis_amt).toFixed(2));
            } else {
                var total1 = price * qty;
                if (type === 'p') {
                    var discountAmount = ((price * qty * discount) / 100);
                    total1 = ((price * qty) - discountAmount);
                } else if (type === 'f') {
                    total1 = (price * qty) - discount;
                }
                row.find("input[name='tax_amount[]']").val("0.00");
                row.find('input[name="total_price[]"]').val(total1.toFixed(2));
            }
            update();
        });

        $(document).on("input", '.calculate', function(){
            var row = $(this).closest('tr');
            var qty = parseFloat(row.find('input[name="qty[]"]').val()) || 0;
            var price = parseFloat(row.find('input[name="price[]"]').val()) || 0;
            var discount = parseFloat(row.find('input[name="discount[]"]').val()) || 0;
            var type = row.find('select[name="discount_type[]"]').val();
            var tax_id = parseFloat(row.find('input[name="tax_percent[]"]').val()) || 0;

            if(type == 'p' && price > 0 && qty > 0){
                var gst = tax_id / 100;
                var gstrate = qty * price * gst;
                var discountAmount = (price * qty * discount) / 100;
                var product_total = ((price * qty) - discountAmount) + gstrate;
                row.find("input[name='tax_amount[]']").val(gstrate.toFixed(2));
                row.find('input[name="total_price[]"]').val(product_total.toFixed(2));
            } else if(type == 'f' && price > 0 && qty > 0){
                var gst = tax_id / 100;
                var gstrate = qty * price * gst;
                let val = (((qty * price) - discount) + gstrate);
                row.find("input[name='tax_amount[]']").val(gstrate.toFixed(2));
                row.find('input[name="total_price[]"]').val(val.toFixed(2));
            } else if(tax_id > 0 && price > 0 && qty > 0){
                var gst = tax_id / 100;
                var gstrate =  (qty * price) * gst;
                row.find("input[name='tax_amount[]']").val(gstrate.toFixed(2));
                var total_price = (qty * price) + gstrate;
                row.find('input[name="total_price[]"]').val((total_price.toFixed(2)));
            } else {
                row.find("input[name='tax_amount[]']").val("0.00");
                row.find('input[name="total_price[]"]').val((qty*price).toFixed(2));
            }
            update();
        });

        $(document).on("change", '.discount_type', function(){
            var row = $(this).closest('tr');
            var qty = parseFloat(row.find('input[name="qty[]"]').val()) || 0;
            var price = parseFloat(row.find('input[name="price[]"]').val()) || 0;
            var discount = row.find('input[name="discount[]"]');
            var tax_amount = parseFloat(row.find("input[name='tax_amount[]']").val()) || 0;

            if ($(this).val() === "f" || $(this).val() === "p") {
                if($(this).val() === "f"){
                    discount.val(0);
                    row.find('input[name="total_price[]"]').val(((qty*price) + tax_amount).toFixed(2));
                } else {
                    discount.val(0);
                    row.find('input[name="total_price[]"]').val(((qty*price) + tax_amount).toFixed(2));
                }
                discount.prop('readonly', false);
                discount.prop('required', true);
            } else {
                discount.val(0);
                discount.prop('readonly', true);
                discount.prop('required', false);
                row.find('input[name="total_price[]"]').val(((qty*price) + tax_amount).toFixed(2));
            }
            update();
        });

        $(document).on("input", '.discount', function(){
            var row = $(this).closest('tr');
            var type = row.find('select[name="discount_type[]"]').val();
            var qty = parseFloat(row.find('input[name="qty[]"]').val()) || 0;
            var price = parseFloat(row.find('input[name="price[]"]').val()) || 0;
            var discount = parseFloat($(this).val()) || 0;
            var tax_amount = parseFloat(row.find("input[name='tax_amount[]']").val()) || 0;
            var act_amt = price * qty;
        
            if (type === 'p') {
                if(act_amt != 0){
                    if(discount < 100){
                        var discountAmount = (price * qty * discount) / 100;
                        var pro_total = ((price * qty) - discountAmount) + tax_amount;
                    } else {                        
                        $(this).val(0);
                        var pro_total = (price * qty);
                        toastr.error("Percentage must be between 0 and 99");
                    }
                } else {
                    $(this).val(0);
                    var pro_total = (price * qty) + tax_amount;
                    toastr.error("First enter the price & qty");
                }
            } else {
                if(act_amt > discount){
                    if(price > 0 && qty > 0){
                        var pro_total = ((price * qty) - discount) + tax_amount;
                    } else {
                        var pro_total = (price * qty) - discount;
                    }
                } else {
                    $(this).val(0);
                    var pro_total = (price * qty) + tax_amount;
                    toastr.error("Flat discount less than "+ pro_total);
                }
            }
            row.find('input[name="total_price[]"]').val(pro_total.toFixed(2));
            update();
        });
    })(jQuery);

    function update(input = ''){
        var shipping_cost = parseFloat($('#shipping_cost').val()) || 0;
        var order_discount = parseFloat($('#order_discount').val()) || 0;
        var total = 0;
        $($('#product').find('tbody tr')).each(function(){
            total += parseFloat($(this).closest('tr').find('input[name="total_price[]"]').val()) || 0;
        });
        var final = (total + shipping_cost) - order_discount;
        if(input == 'discount'){
            var val = total + shipping_cost;
            if(val > order_discount){
                var final = val - order_discount;
            } else {
                final = val;
                $('#order_discount').val(0);
                toastr.error("Discount amount less than " + final);
            }
        }
        $('#total').val(final.toFixed(2));
        $('#custom_total').val(final.toFixed(2));
    }


    $('#add_more').on('click',function() {
        var newRow = `<tr>
                        <td><input type="text" name="name[]" placeholder="Name" class="form-control" required /></td>
                        <td><input type="text" name="code[]" placeholder="Code" class="form-control" required /></td>
                        <td><input type="number" name="qty[]" value="0" class="form-control calculate" required /></td>
                        <td><input type="number" name="price[]" value="0.00" class="form-control calculate" required /></td>
                        <td><select name="discount_type[]" class="form-control discount_type">
                                <option value="">Select</option>"
                                <option value="f">Flat</option>"
                                <option value="p">Percentage</option>"
                            </select>
                        </td>
                        <td><input type="number" name="discount[]" value="0" class="form-control discount" readonly /></td>
                        <td><input type="number" name="tax_percent[]" value="0" class="form-control tax_percent" step="any" min="0" /></td>
                        <td><input type="number" name="tax_amount[]" value="0.00" class="form-control tax_amount" readonly /></td>
                        <td><input type="number" name="total_price[]" value="0.00" class="form-control" readonly/></td>
                        <td><button type="button" class="btn btn-danger" onclick="remove_row('product',this)"><i class="fa fa-close"></i></button></td>
                    </tr>`;
        $('#product').append(newRow);
    });

    function remove_row(tableId, button) {
        var table = $('#' + tableId);
        var row = $(button).closest('tr');
        if (table.find('tbody tr').length > 1) {
            row.remove();
            update();
        } else {
            toastr.error("At least one row must required!");
        }
    }