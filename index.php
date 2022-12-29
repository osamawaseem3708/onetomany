<?php
require ('connection.php');
require ('functions.php');
$result = mysqli_query($con, 'select * from tblproduct where stockinhand > 0');
$branchid = 1;
$flag = $prodid = $employeeid = $stockinhand = $finishedqty = $uom_head = $remarks = '';
$predate = date('Y-m-d');

function getProductDropDownOptions($result)
{
    $select = ' <option value="">-- Choose --</option>';
    while ($row = mysqli_fetch_assoc($result)) {
        $select .= '<option value="' . $row['prodid'] . '">' .  $row['prodname'] . '</option>';
    }
    echo $select;
}

?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>

<div class="container mt-2 mb-1" style="border: 1px solid green" id="page-container">
    <form method="post" action="save.php">
    <div class="row">
        <!-- DROP DOWN FOR PRODUCT NAME -->
        <div class="col-md-4 mb-10">
            <label for="validationCustom03">Product Name <span style="color: red">*</span></label>
            <select required class="form-control select2" data-select2-id="finished_prodname"
                    name="finished_prodname"
                    id="finished_prodname" <?php if ($flag == 'U') echo "disabled"; ?>
                    onchange="getProductInfo(this)">
                <option value="">Choose</option>
                <?php $product = mysqli_query($con, "SELECT * FROM `tblproduct` p join tblproductcategory c on c.prodtype=p.prodtype where p.branchid='$branchid' and c.production='Y' ORDER BY `prodid` DESC");
                while ($row = mysqli_fetch_assoc($product)) { ?>
                    <option value="<?php echo $row['prodid']; ?>" <?php if ($row['prodid'] == $prodid) {
                        echo "selected=selected";
                    } ?>><?php echo htmlentities($row['prodname'], ENT_COMPAT, 'UTF-8'); ?></option>
                <?php } ?>

            </select>
            <div class="invalid-feedback">Please provide a valid Name for Product.</div>
        </div>

        <div class="col-md-4 mb-10">
            <label for="validationCustom03">Production Date <span
                        style="color: red">*</span></label>

            <input type="date" class="form-control" id="validationCustom03" id="predate"
                   name="predate" readonly value="<?php echo $predate; ?>" required>
            <div class="invalid-feedback">Please provide a valid Supplier Address.</div>

        </div>

        <div class="col-md-4 mb-10">

            <label for="validationCustom03">Employee Name <span style="color: red">*</span></label>
            <select class="form-control select2" name="employeeid" required
                    data-select2-id="employee" id="employeeid">
                <option value="">Choose</option>
                <?php $employee = mysqli_query($con, "SELECT * FROM `tblemployee` where company_id ='$branchid'");
                while ($row = mysqli_fetch_assoc($employee)) { ?>
                    <option value="<?php echo $row['eid']; ?>" <?php if ($row['eid'] == $employeeid) {
                        echo "selected=selected";
                    } ?>><?php echo htmlentities($row['fname'] . " " . $row['lname'], ENT_COMPAT, 'UTF-8'); ?></option>
                <?php } ?>
            </select>
            <div class="invalid-feedback">Please provide a valid Employee name.</div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2 mb-10">
            <label for="validationCustom03">Stock In Hand</label>
            <input type="number" id="stockinhand_head" readonly class="form-control"
                   placeholder="10" name="stockinhand_head" value="<?php echo $stockinhand; ?>"
                   step="any" required>
            <div class="invalid-feedback">Please provide a valid Purchase Date.</div>
        </div>

        <div class="col-md-2 mb-10">

            <label for="validationCustom03">Finished Qty <span style="color: red">*</span></label>
            <input type="number" class="form-control" id="validationCustom03" placeholder="0"
                   name="finishedqty" id="finishedqty" step="any" min="0"
                   value="<?php echo $finishedqty; ?>" required>
            <div class="invalid-feedback">Please provide a valid Finished qty.</div>
        </div>

        <div class="col-md-4 mb-10">
            <label for="validationCustom03">Uom For Finished Quantity <span style="color: red">*</span></label>
            <select class="form-control select2" required id="uom_head"
                    name="uom_head">
                <option value="">Select UOM</option>
                <?php
                $q = mysqli_query($con, "select * from tbluom");
                while ($row = mysqli_fetch_assoc($q)) { ?>
                    <option value="<?php echo $row['longname'] ?>" <?php if ($uom_head == $row['longname']) echo "selected=selected"; ?>>
                        <?php echo $row['longname']; ?>
                    </option>
                <?php } ?>
            </select>

        </div>

        <div class="col-md-4 mb-10">

            <label for="validationCustom03">Remarks</label>
            <input type="text" class="form-control" id="validationCustom03" placeholder="Remarks"
                   name="remarks" id="remarks" value="<?php echo $remarks; ?>">
            <div class="invalid-feedback">Please provide a valid Remarks.</div>
        </div>
    </div>

    <div class="container mt-3" style="border: 1px solid black" id="main-detail-container">
        <div class="row">
            <div class="col-md-12 text-center display-6">
                <strong>DETAIL FORM</strong>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <label for="prodname">Product Name</label>
                <select id="prodname" class="form-control custom-select" name="prodname"
                        onchange="getProductInfo(this,'DH')">
                    <?= getProductDropDownOptions($result); ?>
                </select>
            </div>

            <div class="col-md-1">
                <label for="price">Price</label>
                <input type="price" placeholder="price" class="form-control" readonly name="price" id="price">
            </div>

            <div class="col-md-1">
                <label for="stock_in_hand">Stock</label>
                <input type="number" placeholder="stock in hand" class="form-control" readonly name="stock_in_hand"
                       id="stock_in_hand">
            </div>
            <div class="col-md-2">
                <label for="qty">Product Quantity</label>
                <input type="number" name="qty" placeholder="qty" id="qty" onkeyup="calculateTotal()"
                       class="form-control">
            </div>
                <div class="col-md-2">
                    <label for="uom">UOM</label>
                    <select id="uom" class="form-control custom-select" name="uom">
                        <?= getUomDropDownOptions($con); ?>
                    </select>

                </div>
            <div class="col-md-2">
                <label for="amount">Product Amount</label>
                <input type="number" name="amount" readonly placeholder="amount" id="amount" class="form-control">
            </div>
            <div class="col-md-1">
                <label for="add">Action</label>
                <input type="button" value="Add" id="add" onclick="AddItemInTable()" class="btn btn-primary">
            </div>
        </div>

        <!--  NOW MAKING TABLE CONTAINER-->

        <div class="row">
            <div class="col-md-12">
                    <table class="table table stripped" id="detailTbl">
                        <tr>
                            <th>NAME</th>
                            <th>PRICE</th>
                            <th>STOCK IN HAND</th>
                            <th>QTY</th>
                            <th>AMOUNT</th>
                            <th>ACTION</th>
                        </tr>
                        <tbody id="table-body"></tbody>
                    </table>
            </div>
        </div>

    </div>
    <div class="row mt-2 mb-2">
        <div class="col-md-12">
            <input type="submit" name="submit" value="Submit" class="btn btn-success">
        </div>
    </div>
    </form>
</div>


<script>
    function getProductInfo(select_element, flag = 'H') {
        var id = select_element.value;
        $.ajax({
            url: "getprodinfo.php",
            method: "POST",
            data: {
                id: id
            },
            success: function (response) {
                response = JSON.parse(response);
                if (response.responseCode === 200) {
                    var price = response.responseData.prodprice;
                    var stockinhand = response.responseData.stockinhand;
                    var uom = response.responseData.produom;
                    if (flag == 'DH') {
                        $('#price').val(price);
                        $('#stock_in_hand').val(stockinhand);
                        $('#uom').val(uom);
                    } if(flag=='D') {
                        //getting previous value with this object
                        var previous_id = $(select_element).attr("data-prev")
                        $('#detail_price_' + previous_id).val(price);
                        $('#detail_stockinhand_' + previous_id).val(stockinhand);
                        var x = $('#detail_qty_' + previous_id);
                        calculateTotal(x);
                    }
                    if(flag=='H'){
                        $('#stockinhand_head').val(stockinhand);
                    }
                }

            }
        });
    }


    //    function to calclulate total quantity with price of product
    function calculateTotal(quantity_element = '') {

        if (quantity_element != "") {
            var previous_id = $(quantity_element).attr("data-prev-qty");
            var qty = $('#detail_qty_' + previous_id).val();
            var stock_in_hand = $('#detail_stockinhand_' + previous_id).val();
            var price = $('#detail_price_' + previous_id).val();
        } else {
            var qty = $('#qty').val();
            var stock_in_hand = $('#stock_in_hand').val();
            var price = $('#price').val();
        }

        if (+qty > +stock_in_hand) {
            alert('Quantity cannot be greater than the stock');
            $('#add').attr('disabled', true)
            if (quantity_element != "") {
                var previous_id = $(quantity_element).attr("data-prev-qty")
                $('#detail_qty_' + previous_id).css('background-color', 'red');
            } else {
                $('#qty').css('background-color', 'red');
            }
            return;
        } else {
            var total = +qty * +price;
            $('#add').attr('disabled', false)
            if (quantity_element != "") {
                var previous_id = $(quantity_element).attr("data-prev-qty")
                $('#detail_qty_' + previous_id).css('background-color', 'white');
                $('#detail_amount_' + previous_id).val(total)
            } else {
                $('#amount').val(total)
                $('#qty').css('background-color', 'white');
            }
        }
    }

    function AddItemInTable() {
        //    GETTING VALUES FROM INPUT FIELDS
        var prodid = $('#prodname').val();
        var price = $('#price').val();
        var stock_in_hand = $('#stock_in_hand').val();
        var qty = $('#qty').val();
        var amount = $('#amount').val();

        if (prodid.length > 0 && price.length > 0 && stock_in_hand.length > 0 && qty.length > 0 && amount.length > 0) {
            //  NOW ADDING ADDED TEXT FROM INPUT FIELDS  TO TABLE BODYI

            //    Making select first with selected_index
            var select_box = getProductSelectBoxForTableRow(prodid);
            var price_text_box = getPriceForTableRow(prodid);
            var stockinhand_text_box = getStockInHandForTableRow(prodid);
            var qty_text_box = getQtyForTableRow(prodid);
            var uom_text_box= getUomSelectBoxForTableRow(prodid);
            var amount_text_box = getAmountForTableRow(prodid);
            var remove_icon_row = '<strong style="color: red;font-weight: bold;text-align: center" onclick="del_row(this)"> X </strong>';

            var tr = '<tr><td style="width: 40%">' + select_box + '</td><td style="width: 10%">' + price_text_box + '</td><td style="width: 10%">' + stockinhand_text_box + '</td><td style="width: 10%">' + qty_text_box + '</td><td style="width: 10%">' + uom_text_box + '</td><td style="width: 10%">' + amount_text_box + '</td><td style="width: 10%">' + remove_icon_row + '</td></tr>';
            $('#table-body').append(tr);
            setValueOfAppendedFieldsInTableRow(prodid, price, stock_in_hand, qty, amount);
        } else {
            alert('Fields Cannot be Empty');
            //GENERATE ERROR MESSAGE
        }
    }

    function getProductSelectBoxForTableRow(prodid) {
        var select = '<select data-prev="' + prodid + '" id="detail_prodid_' + prodid + '" class="form-control custom-select" name="detail_prodid[]" onchange="getProductInfo(this,`D`)">';
        select += `<?php $result = mysqli_query($con, 'select * from tblproduct where stockinhand > 0'); getProductDropDownOptions($result);?>`
        select += '</select>';
        return select;
    }

    function getPriceForTableRow(prodid) {
        return '<input step="any" type="number" id="detail_price_' + prodid + '" name="detail_price[]" class="form-control">';
    }

    function getStockInHandForTableRow(prodid) {
        return '<input step="any" type="number" id="detail_stockinhand_' + prodid + '" name="detail_stockinhand[]" class="form-control">';
    }

    function getQtyForTableRow(prodid) {
        return '<input step="any" type="number" onkeyup="calculateTotal(this)"  data-prev-qty="' + prodid + '"  id="detail_qty_' + prodid + '" name="detail_qty[]" class="form-control">';
    }

    function getUomSelectBoxForTableRow(prodid) {
        var select = '<select data-prev="' + prodid + '" id="detail_uom_' + prodid + '" class="form-control custom-select" name="detail_uom[]" onchange="getProductInfo(this,`D`)">';
        select += `<?php getUomDropDownOptions($con);;?>`
        select += '</select>';
        return select;
    }

    function getAmountForTableRow(prodid) {
        return '<input step="any" type="number" id="detail_amount_' + prodid + '" name="detail_amount[]" class="form-control">';
    }

    function setValueOfAppendedFieldsInTableRow(prodid, price, stockinhand, qty, amount) {
        //Setting value for appended fields
        $('#detail_prodid_' + prodid + ' option[value="' + prodid + '"]').attr('selected', 'selected');
        $('#detail_price_' + prodid).val(price)
        $('#detail_stockinhand_' + prodid).val(stockinhand)
        $('#detail_qty_' + prodid).val(qty)
        $('#detail_amount_' + prodid).val(amount)

        //    Clearing head fields
        $('#prodname').val('');
        $('#price').val('');
        $('#stock_in_hand').val('');
        $('#qty').val('');
        $('#amount').val('');
        $('#uom').val('');
    }

    function del_row(r) {

        var i = r.parentNode.parentNode.rowIndex;
        var table = document.getElementById("detailTbl");
        var totalRowCount = table.rows.length - 1;
        if (totalRowCount > 1) {
            document.getElementById("detailTbl").deleteRow(i);
        }

    }

</script>