<script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<?php
//$con='';
require ('../connection.php');
require ('dropfunctions.php');
?>

<div class="container">
    <div class="row mt-3">
        <div class="col-md-12 text-center">
            <h3>Populate Offers Dropdown And Fill OFFER ITEMS IN DETAIL DROPDOWN</h3>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <label for="offer-dropdown">OFFER</label>
            <select id="offer-dropdown" name="offer_dropdown" class="form-control"
                    onchange="CallAjaxFunction(this,'OFD')">
                <?php getAllOffers ($con); ?>
            </select>
        </div>
        <div class="col-md-6">
            <label for="offer-detail-dropdown">Offer Detail</label>
            <select id="offer-detail-dropdown" name="offer_detail_dropdown" class="form-control">
            </select>
        </div>
    </div>
</div>


<script>
    function CallAjaxFunction(select_element, call_for) {
        var function_name = '';

        var id = $(select_element).val();
        if (call_for === 'OFD') {
            //    M Mean ajax request would be for get menu detail
            function_name = 'getOfferDetailBasedOnID';
        }
        if (function_name.length > 0) {
            $.ajax({
                url: "dropfunctions.php?function=" + function_name,
                method: "POST",
                data: {
                    id: id
                },
                success: function (response) {
                    response = JSON.parse(response);
                    if (response.responseCode === 200) {
                        $('#offer-detail-dropdown').html(response.options)
                    }
                }
            });
        }

    }
</script>
