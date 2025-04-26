<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
	.show_hide_columns {
		position: absolute;
		z-index: 5000;
		left: 204px
	}
</style>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<?php $this->load->view('quotations/list_template'); ?>
		</div>
	</div>
</div>

<?php init_tail(); ?>
<script>
	$(document).ready(function() {
		var table = $('.table-pur_estimates').DataTable();

		// Handle "Select All" checkbox
		$('#select-all-columns').on('change', function() {
			var isChecked = $(this).is(':checked');
			$('.toggle-column').prop('checked', isChecked).trigger('change');
		});

		// Handle individual column visibility toggling
		$('.toggle-column').on('change', function() {
			var column = table.column($(this).val());
			column.visible($(this).is(':checked'));

			// Sync "Select All" checkbox state
			var allChecked = $('.toggle-column').length === $('.toggle-column:checked').length;
			$('#select-all-columns').prop('checked', allChecked);
		});

		// Sync checkboxes with column visibility on page load
		table.columns().every(function(index) {
			var column = this;
			$('.toggle-column[value="' + index + '"]').prop('checked', column.visible());
		});

		// Prevent dropdown from closing when clicking inside
		$('.dropdown-menu').on('click', function(e) {
			e.stopPropagation();
		});
	});
</script>
</body>

</html>