<table>
	<tbody>
		<?php
		$commodity_descriptions = json_decode($commodity_descriptions, true);
		$row_index = 0;
		foreach ($list_id as $key => $id) {
			$row_index++; ?>
			<tr>
				<td width="50%">
					<?php
					$item_data = $this->warehouse_model->get_item_longdescriptions($id);
					if ($item_data) {
					?>
						<img src="<?php echo $this->warehouse_model->fe_get_item_image_qrcode_pdf($item_data->id); ?>" width="400">
					<?php } ?>
				</td>
				<td width="50%">
					<p style="padding-top: 10px;"><strong><?php echo _l('Commodity Code'); ?> : </strong><?php echo $item_data->commodity_code . "_" . $item_data->description;; ?></p>
					<p><strong><?php echo _l('supplier_name'); ?> : </strong><?php echo $vendor; ?></p>
					<p><strong><?php echo _l('Purchase Order'); ?> : </strong><?php echo $pur_order; ?></p>
					<p><strong><?php echo _l('project'); ?> : </strong><?php echo $project_name; ?></p>
					<p><strong><?php echo _l('Description'); ?> : </strong>
						<?php
						echo isset($commodity_descriptions[$key])
							? $commodity_descriptions[$key]
							: 'N/A';
						?>
					</p>
				</td>
			</tr>
			<tr>
				<td colspan="2" style="height:30px;"></td>
			</tr>
			<?php
			// After every 2 records, insert a page break row
			if ($row_index % 2 == 0) { ?>
				<tr style="page-break-after: always;">
					<td colspan="2">&nbsp;</td>
				</tr>
		<?php }
		} ?>
	</tbody>
</table>