<table cellpadding="5">
	<tbody>
		<?php 
		$row_index = 0;
		foreach ($list_id as $key => $id) {
			if($row_index == 0){
				$row_index++;
				?>
				<tr>
				<?php } ?>
				<td width="25%">
					<?php $item_data = $this->warehouse_model->get_item_longdescriptions($id);
					if($item_data){
						?>
						<img src="<?php echo $this->warehouse_model->fe_get_item_image_qrcode_pdf($item_data->id); ?>"> 
						<p style="text-align: center;"><?php echo $item_data->commodity_code."<br/>".$item_data->description; ?></p>
					<?php } ?>
				</td>
				<?php if(($key+1) % 4 == 0){
					$row_index = 0;
					?>
				</tr>
			<?php } ?>
		<?php }
		if($row_index != 0){
			for ($i=0; $i < (4-$row_index); $i++) { ?>
				<td></td>
			<?php } ?>
		</tr>
	<?php } ?>
</tbody>
</table>