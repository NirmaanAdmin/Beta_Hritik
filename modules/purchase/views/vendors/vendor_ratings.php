<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="panel_s">
    <div class="panel-body">
        <h4 class="no-margin">Vendor Ratings</h4>
        <hr>
        <?php
        if($ratings == null){ ?>
            <div class="clearfix">
            <button class="btn btn-primary pull-right" type="button" data-toggle="modal" data-target="#rateVendorModal">
                Rate Vendor
            </button>
        </div>
        <?php  }
        ?>

        <br>
        <table class="table dt-table">
            <thead>
                <tr>
                    <th>Rating Date</th>
                    <th>Quality</th>
                    <th>Delivery</th>
                    <th>Pricing</th>
                    <th>Service</th>
                    <th>Compliance</th>
                    <th>Overall Rating</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ratings as $rating) : ?>
                    <tr>
                        <td><?= date('d M Y H:i a', strtotime($rating['rating_date'])); ?></td>
                        <td><?= $rating['quality_rating']; ?></td>
                        <td><?= $rating['delivery_rating']; ?></td>
                        <td><?= $rating['pricing_rating']; ?></td>
                        <td><?= $rating['service_rating']; ?></td>
                        <td><?= $rating['compliance_rating']; ?></td>
                        <td><?= number_format($rating['overall_rating'], 1); ?></td>
                        <td>
                            <button class="btn btn-primary btn-icon edit-rating" onclick="edit_rating()"
                                data-id="<?= $rating['id']; ?>">
                                <i class="fa fa-edit"></i>
                            </button>
                            <a href="<?= admin_url('purchase/delete_rating/' . $rating['id'] . '/' . $vendor_id); ?>" class="btn btn-danger btn-icon">
                                <i class="fa fa-trash"></i>
                            </a>
                        </td>
                        <input type="hidden" name="rating_id" id="rating_id" value="<?= $rating['id']; ?>">
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal for Rating Vendor -->
<div class="modal fade" id="rateVendorModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Rate Vendor</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?= form_hidden('id', ''); ?>
                <?= form_hidden('vendor_id', $vendor_id); ?>
                <div class="form-group">
                    <label for="quality_rating">Quality (1-5)</label>
                    <input type="number" name="quality_rating" class="form-control" min="1" max="5" >
                </div>
                <div class="form-group">
                    <label for="delivery_rating">Delivery (1-5)</label>
                    <input type="number" name="delivery_rating" class="form-control" min="1" max="5" >
                </div>
                <div class="form-group">
                    <label for="pricing_rating">Pricing (1-5)</label>
                    <input type="number" name="pricing_rating" class="form-control" min="1" max="5" >
                </div>
                <div class="form-group">
                    <label for="service_rating">Customer Service (1-5)</label>
                    <input type="number" name="service_rating" class="form-control" min="1" max="5" >
                </div>
                <div class="form-group">
                    <label for="compliance_rating">Compliance (1-5)</label>
                    <input type="number" name="compliance_rating" class="form-control" min="1" max="5" >
                </div>
                <div class="form-group">
                    <label for="comments">Comments</label>
                    <textarea name="comments" class="form-control"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>

<script>
    function edit_rating() {
        var ratingId = $('#rating_id').val();

        url: "<?= admin_url('purchase/get_rating/'); ?>" + ratingId,

            // Fetch rating details using AJAX
            $.ajax({
                url: "<?= admin_url('purchase/get_rating/'); ?>" + ratingId,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    if (data.error) {
                        alert(data.error);
                    } else {
                        // Populate the modal fields with data
                        $('#rateVendorModal input[name="id"]').val(data.id);
                        $('#rateVendorModal input[name="quality_rating"]').val(data.quality_rating);
                        $('#rateVendorModal input[name="delivery_rating"]').val(data.delivery_rating);
                        $('#rateVendorModal input[name="pricing_rating"]').val(data.pricing_rating);
                        $('#rateVendorModal input[name="service_rating"]').val(data.service_rating);
                        $('#rateVendorModal input[name="compliance_rating"]').val(data.compliance_rating);
                        $('#rateVendorModal textarea[name="comments"]').val(data.comments);
                        // Open the modal after data is populated
                        $('#rateVendorModal').modal('show');
                    }
                },
                error: function() {
                    alert('Failed to fetch rating data. Please try again.');
                }
            });
    }
</script>
