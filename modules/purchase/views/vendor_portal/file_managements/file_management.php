<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php hooks()->do_action('app_admin_head'); ?>
<input type="hidden" name="parent_id" value="<?php echo drawing_htmldecode($parent_id); ?>">
<style>
	.dropdown-menu {
		width: 100%;
		border: 1px solid #ddd;
		background-color: #fff;
		max-height: 200px;
		overflow-y: auto;
	}
	.dropdown-item {
		padding: 8px 12px;
		display: block;
		color: #333;
		text-decoration: none;
	}
	.dropdown-item:hover {
		background-color: #f0f0f0;
	}

	.dropdown-item.disabled {
		color: #999;
		pointer-events: none;
	}
	.vendor_email {
		padding-left: 0px;
		padding-right: 1px;
	}
</style>

<div class="row">
	<div class="col-md-12">

		<div class="panel_s">
			<div class="panel-body">

				<div class="row">
					<div class="col-md-12">
						<div class="col-md-3">
							<h4>
								<?php echo drawing_htmldecode($title); ?>
							</h4>
						</div>

						<?php if ($share_to_me == 0) { ?>
							<div class="col-md-9 btn-tool">
								<?php if (isset($item) && $item->filetype == 'folder') {	?>
									<?php /* 
									<button class="btn btn-default pull-right mright10 display-flex default-tool" onclick="open_upload()">
										<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-upload-cloud">
											<polyline points="16 16 12 12 8 16" />
											<line x1="12" y1="12" x2="12" y2="21" />
											<path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3" />
											<polyline points="16 16 12 12 8 16" />
										</svg>
										<span class="mleft5 mtop2">
											<?php echo _l('dmg_upload'); ?>
										</span>
									</button>
									*/ ?>
									<?php if (is_admin()) { ?>
										<?php /* 
										<button class="btn btn-default pull-right mright10 display-flex default-tool" onclick="create_folder()">
											<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-folder-plus">
												<path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z" />
												<line x1="12" y1="11" x2="12" y2="17" />
												<line x1="9" y1="14" x2="15" y2="14" />
											</svg>
											<span class="mleft5 mtop2">
												<?php echo _l('dmg_new_folder'); ?>
											</span>
										</button>
										*/ ?>
									<?php } ?>

									<?php } else {
									if (isset($item) && $edit != 1) {
									?>
										<?php /*
										<button class="btn btn-default pull-right mright10 display-flex bulk-action-btn" onclick="remider(<?php echo drawing_htmldecode($parent_id); ?>)">
											<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bell">
												<path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
												<path d="M13.73 21a2 2 0 0 1-3.46 0" />
											</svg>
											<span class="mleft5 mtop2">
												<?php echo _l('dmg_remind'); ?>
											</span>
										</button>
										*/ ?>
								<?php
									}
								}
								?>

								<!-- For bulk select -->
								<?php /*
								<button class="btn btn-default pull-right mright10 display-flex bulk-action-btn hide" onclick="bulk_move_item()">
									<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-right">
										<polyline points="13 17 18 12 13 7" />
										<polyline points="6 17 11 12 6 7" />
									</svg>
									<span class="mleft5 mtop2">
										<?php echo _l('dmg_move'); ?>
									</span>
								</button>
								*/ ?>

								<a href="<?php echo site_url('purchase/vendors_portal/bulk_download_item?parent_id=' . $parent_id . '&id='); ?>" class="btn btn-default pull-right mright10 display-flex bulk-action-btn bulk-download-btn hide">
									<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download">
										<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
										<polyline points="7 10 12 15 17 10" />
										<line x1="12" y1="15" x2="12" y2="3" />
									</svg>
									<span class="mleft5 mtop2">
										<?php echo _l('dmg_dowload'); ?>
									</span>
								</a>

								<?php /*
								<button class="btn btn-default pull-right mright10 display-flex bulk-action-btn hide" onclick="bulk_duplicate_item()">
									<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-copy">
										<rect x="9" y="9" width="13" height="13" rx="2" ry="2" />
										<path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1" />
									</svg>
									<span class="mleft5 mtop2">
										<?php echo _l('dmg_duplicate'); ?>
									</span>
								</button>
								<button class="btn btn-default pull-right mright10 display-flex bulk-action-btn hide" onclick="bulk_delete_item()">
									<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2">
										<polyline points="3 6 5 6 21 6" />
										<path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
										<line x1="10" y1="11" x2="10" y2="17" />
										<line x1="14" y1="11" x2="14" y2="17" />
									</svg>
									<span class="mleft5 mtop2">
										<?php echo _l('dmg_delete'); ?>
									</span>
								</button>
								*/ ?>
								<!-- For bulk select -->
							</div>
							<?php } else {
							if (($share_to_me == 1 && $parent_id == 0) || (isset($item) && $item->filetype == 'folder' && !drawing_check_share_permission($parent_id, 'upload_only'))) { ?>
								<?php echo render_input('search', '', '', 'text', ['placeholder' => _l('dmg_search_name_tag_etc')], [], 'pull-right default-tool'); ?>
							<?php } ?>
						<?php } ?>
						<div class="col-md-12">
							<hr>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3 border-right">
						<ul class="list-group list-group-flush list-group-custom" role="tablist">
							<?php
							foreach ($root_folder as $key => $value) {
								$active = '';

								if ($master_parent_id == $value['id'] && $share_to_me == 0) {
									$active = ' active';
								}
							?>
								<li class="list-group-item list-group-item-action display-flex<?php echo drawing_htmldecode($active); ?>" data-toggle="list" role="tab">
									<a href="<?php echo site_url('purchase/vendors_portal/drawing_management?id=' . $value['id']); ?>" class="w100">
										<?php echo drawing_htmldecode($value['name']); ?>
									</a>

									<?php /* 
									<div class="dropdown">
										<button class="btn btn-tool pull-right dropdown-toggle" role="button" id="dropdown_menu_<?php echo drawing_htmldecode($value['id']); ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal">
												<circle cx="12" cy="12" r="1" />
												<circle cx="19" cy="12" r="1" />
												<circle cx="5" cy="12" r="1" />
											</svg>
										</button>
										<ul class="dropdown-menu" aria-labelledby="dropdown_menu_<?php echo drawing_htmldecode($value['id']); ?>">
											<li class="no-padding">
												<a href="#" data-name="<?php echo drawing_htmldecode($value['name']); ?>" onclick="edit_section(this, '<?php echo drawing_htmldecode($value['id']); ?>')"><?php echo _l('dmg_edit') ?></a>
											</li>
											<li class="no-padding">
												<a href="#" data-type="<?php echo drawing_htmldecode($value['filetype']); ?>" onclick="share_document(this, '<?php echo drawing_htmldecode($value['id']); ?>')"><?php echo _l('dmg_share') ?></a>
											</li>
											<li class="no-padding">
												<a href="<?php echo site_url('drawing_management/download_folder/' . $value['id']); ?>"><?php echo _l('dmg_dowload') ?></a>
											</li>
											<?php if ($value['is_primary'] == 0) { ?>
												<li class="no-padding">
													<a class="_swaldelete" href="<?php echo site_url('drawing_management/delete_section/' . $value['id'] . '/' . $parent_id) ?>"><?php echo _l('dmg_delete') ?></a>
												</li>
											<?php } ?>
										</ul>
									</div>
									*/ ?>

								</li>
							<?php } ?>
							<?php /* <li class="list-group-item list-group-item-action">
								<a href="javascript:void(0)" onclick="create_new_section()">
									<i class="fa fa-plus"></i> <?php echo _l('dmg_create_new_section'); ?>											
								</a>
							</li> */ ?>
						</ul>
						<hr>
						<ul class="list-group list-group-flush list-group-custom" role="tablist">
							<?php /* <li class="list-group-item list-group-item-action display-flex<?php echo ($share_to_me == 1 ? ' active' : ''); ?>" data-toggle="list" role="tab">
								<a href="<?php echo site_url('drawing_management?share_to_me=1&id=0'); ?>" class="w100 display-flex">
									<svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-share-2"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>  
									<span class="mtop2 mleft5">
										<?php echo _l('dmg_share_to_me'); ?>
										<?php 
										$share_items = $this->drawing_management_model->get_item('','id IN ('.$share_id.')', 'name, id, dateadded, filetype');											
										if($share_items && is_array($share_items) && count($share_items) > 0){ ?>
											<span class="label bg-warning mleft10"><strong><?php echo count($share_items); ?></strong></span>
										<?php } ?>
									</span>
								</a>
							</li> */ ?>

							<?php /* 
							<li class="list-group-item list-group-item-action display-flex<?php echo ($my_approval == 1 ? ' active' : ''); ?>" data-toggle="list" role="tab">
								<a href="<?php echo site_url('drawing_management?my_approval=1&id=0'); ?>" class="w100 display-flex">
									<svg viewBox="0 0 24 24" width="23" height="23" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
										<polyline points="9 11 12 14 22 4"></polyline>
										<path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
									</svg>
									<span class="mtop2 mleft5">
										<?php echo _l('dmg_my_approval'); ?>
										<?php if ($approve_items && is_array($approve_items) && count($approve_items) > 0) { ?>
											<span class="label bg-warning mleft10"><strong><?php echo count($approve_items); ?></strong></span>
										<?php } ?>
									</span>
								</a>
							</li>
							<li class="list-group-item list-group-item-action display-flex<?php echo ($electronic_signing == 1 ? ' active' : ''); ?>" data-toggle="list" role="tab">
								<a href="<?php echo site_url('drawing_management?electronic_signing=1&id=0'); ?>" class="w100 display-flex">
									<svg viewBox="0 0 24 24" width="23" height="23" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
										<polyline points="9 11 12 14 22 4"></polyline>
										<path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
									</svg>
									<span class="mtop2 mleft5">
										<?php echo _l('dmg_electronic_signing'); ?>
										<?php if ($approve_item_eids && is_array($approve_item_eids) && count($approve_item_eids) > 0) { ?>
											<span class="label bg-warning mleft10"><strong><?php echo count($approve_item_eids); ?></strong></span>
										<?php } ?>
									</span>
								</a>
							</li>
							*/ ?>
						</ul>
					</div>


					<div class="col-md-9">

						<?php /*
						<div class="col-md-12 mtop15">
							<div class="col-md-2">
								<?php
								$module_name = 'drawing_management';
								$design_stage_filter = get_module_filter($module_name, 'design_stage');
								$design_stage_filter_val = !empty($design_stage_filter) ? $design_stage_filter->filter_value : '';
								$statuses = [
									0 => ['id' => 'Concept Design', 'name' => 'Concept Design'],
									1 => ['id' => 'Schematic Design', 'name' => 'Schematic Design'],
									2 => ['id' => 'Design Development', 'name' => 'Design Development'],
									3 => ['id' => 'Construction Documents', 'name' => 'Construction Documents'],
								];

								// echo render_select('design_stage', $statuses, array('id', 'name'), 'design_stage', $design_stage_filter_val, array('data-width' => '100%', 'data-none-selected-text' => _l('design_stage'),  'data-actions-box' => true), array(), 'no-mbot', '', true); 
								?>
								<label for="design_stage">Design Stage</label>
								<select name="design_stage" id="design_stage_filter" class="form-control selectpicker" style="width: 100%;" data-actions-box="true" data-live-search="true">
									<option value=""></option>
									<?php foreach ($statuses as $status): ?>
										<option value="<?php echo htmlspecialchars($status['id']); ?>"
											<?php echo ($status['id'] == $design_stage_filter_val) ? 'selected' : ''; ?>>
											<?php echo htmlspecialchars($status['name']); ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="col-md-2">
								<label for="discipline">Discipline</label>
								<select id="discipline_filter" name="discipline[]" data-live-search="true" class="form-control selectpicker" multiple>
									<option value=""></option>
									<?php
									$discipline_filter = get_module_filter($module_name, 'discipline');
									$discipline_filter_val = !empty($discipline_filter) ? $discipline_filter->filter_value : '';

									// Convert comma-separated values to an array
									$selected = !empty($discipline_filter_val) ? explode(',', $discipline_filter_val) : [];

									foreach ($discipline as $option) : ?>
										<option value="<?= $option['id']; ?>" <?= in_array($option['id'], $selected) ? 'selected' : ''; ?>>
											<?= $option['name']; ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="col-md-2">
								<?php
								$purpose_filter = get_module_filter($module_name, 'purpose');
								$purpose_filter_val = !empty($purpose_filter) ? $purpose_filter->filter_value : '';
								?>

								<label for="purpose" class="control-label"><?php echo _l('purpose'); ?></label>
								<select id="purpose_filter" name="purpose" class="selectpicker" data-width="100%" data-none-selected-text="None selected" tabindex="-98">
									<option value=""></option>
									<option value="Issued for Information" <?= ($purpose_filter_val == "Issued for Information") ? 'selected' : ''; ?>>Issued for Information</option>
									<option value="Issued for review" <?= ($purpose_filter_val == "Issued for review") ? 'selected' : ''; ?>>Issued for review</option>
									<option value="Issued for approval" <?= ($purpose_filter_val == "Issued for approval") ? 'selected' : ''; ?>>Issued for approval</option>
									<option value="Issued for tender" <?= ($purpose_filter_val == "Issued for tender") ? 'selected' : ''; ?>>Issued for tender</option>
									<option value="Issued for construction" <?= ($purpose_filter_val == "Issued for construction") ? 'selected' : ''; ?>>Issued for construction</option>
								</select>
							</div>
							<div class="col-md-2">
								<?php
								$status_filter = get_module_filter($module_name, 'status');
								$status_filter_val = !empty($status_filter) ? $status_filter->filter_value : '';
								?>
								<label for="status" class="control-label"><?php echo _l('status'); ?></label>
								<select id="status_filter" name="status" class="selectpicker" data-width="100%" data-none-selected-text="None selected" tabindex="-98">
									<option value=""></option>
									<option value="under_review" <?= ($status_filter_val == "under_review") ? 'selected' : ''; ?>>Under Review</option>
									<option value="released" <?= ($status_filter_val == "released") ? 'selected' : ''; ?>>Released</option>
									<option value="released_with_comments" <?= ($status_filter_val == "released_with_comments") ? 'selected' : ''; ?>>Released with comments</option>
									<option value="rejected" <?= ($status_filter_val == "rejected") ? 'selected' : ''; ?>>Rejected</option>
								</select>
							</div>
							<div class="col-md-2">
								<?php
								$controlled_document_filter = get_module_filter($module_name, 'controlled_document');
								$controlled_document_filter_val = !empty($controlled_document_filter) ? $controlled_document_filter->filter_value : '';
								?>
								<label for="controlled_document" class="control-label"><?php echo _l('Controlled Document'); ?></label>
								<select id="controlled_document_filter" name="controlled_document" class="selectpicker" data-width="100%" data-none-selected-text="None selected" tabindex="-98">
									<option value=""></option>
									<option value="1" <?= ($controlled_document_filter_val == 1) ? 'selected' : ''; ?>>Yes</option>
									<option value="0" <?= ($controlled_document_filter_val === 0) ? 'selected' : ''; ?>>No</option>
								</select>
							</div>
							<div class="col-md-2" style="margin-top: 22px;">
								<a href="javascript:void(0)" class="btn btn-info btn-icon reset_vbt_all_filters">
									Reset filter </a>
							</div>
						</div>
						*/ ?>

						<div class="col-md-12 mtop15">
							<div id="append_fillter_data">
								<?php if ($share_to_me == 0 && $my_approval == 0 && $electronic_signing == 0) { ?>
									<div class="row">
										<div class="col-md-12">
											<?php
											$html_breadcrumb = '';
											$data_breadcrumb = $this->drawing_management_model->breadcrum_array($parent_id);
											foreach ($data_breadcrumb as $key => $value) {
												$html_breadcrumb = '<li class="breadcrumb-item"><a href="' . site_url('purchase/vendors_portal/drawing_management?id=' . $value['id']) . '">' . $value['name'] . '</a></li>' . $html_breadcrumb;
											}
											$col_class = '';
											?>
											<?php if ($value['id'] == 1) {
												$col_class = 'col-md-9';
											} elseif ($value['id'] == 2) {
												$col_class = 'col-md-7';
											} else {
												$col_class = 'col-md-8';
											} ?>

											<nav aria-label="breadcrumb">
												<ol class="breadcrumb <?= $col_class ?>">
													<?php echo drawing_htmldecode($html_breadcrumb); ?>
												</ol>
												<?php /*
												<?php if ($value['id'] == 1) { ?>
													<h5 class="text-muted display-flex col-md-3" style="border-bottom: 1px solid #f0f0f0;padding-bottom: 18px !important;justify-content: end;padding: 0px; margin-top: 0px;">

														<span class="mtop3 mleft5">These are your private files</span>
													</h5>
												<?php	} elseif ($value['id'] == 2) { ?>
													<h5 class="text-muted display-flex col-md-5" style="border-bottom: 1px solid #f0f0f0;padding-bottom: 18px !important;justify-content: end;padding: 0px; margin-top: 0px;">

														<span class="mtop3 mleft5">These files are viewable by entire company</span>
													</h5>
												<?php } else { ?>
													<h5 class="text-muted display-flex col-md-4" style="border-bottom: 1px solid #f0f0f0;padding-bottom: 18px !important;justify-content: end;padding: 0px; margin-top: 0px;">

														<span class="mtop3 mleft5">These are project specific design files</span>
													<?php 	}
													?>
												 */ ?>
											</nav>
										</div>
									</div>
									<?php
									if (isset($item)) {
										if (isset($item) && $item->filetype == 'folder') {
											$child_items = $this->drawing_management_model->get_item('', 'parent_id = ' . $parent_id, 'name, id, dateadded, filetype,parent_id');
											if (count($child_items)) {
												$this->load->view('file_managements/includes/item_list.php', ['child_items' => $child_items]);
											} else { ?>
												<div class="row mbot20">
													<div class="col-md-12">
														<h5 class="text-muted display-flex">
															<span class="text-warning">
																<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-zap">
																	<polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2" />
																</svg>
															</span>
															<span class="mtop3 mleft5"><?php echo _l('dmg_the_folder_is_empty_you_can_create_a_folder_or_upload_a_file') . '.'; ?></span>
														</h5>
													</div>
												</div>
											<?php } ?>

											<?php /*
											<div class="file-form-group file-form">
												<?php echo form_open_multipart(site_url('drawing_management/upload_file/' . $parent_id), array('id' => 'form_upload_file')); ?>
												<input type="file" id="files" name="file[]" multiple="">
												<div class="file-form-preview hide">
													<ul class="selectedFiles list-group list-group-flush mtop15" id="selectedFiles"></ul>
													<hr>
													<button class="btn btn-primary pull-right mright10 display-flex">
														<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-upload">
															<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
															<polyline points="17 8 12 3 7 8" />
															<line x1="12" y1="3" x2="12" y2="15" />
														</svg>
														<span class="mleft5 mtop2">
															<?php echo _l('dmg_upload_all'); ?>
														</span>
													</button>
												</div>
												<?php echo form_close(); ?>
											</div>
											*/ ?>
									<?php } else {
											if ($edit == 1) {
												$this->load->view('file_managements/includes/file_edit.php');
											} else {
												$this->load->view('file_managements/includes/file_detail.php');
											}
										}
									} ?>
								<?php } else { ?>

									<!-- Share to me -->
									<?php if ($share_to_me == 1) { ?>
										<?php if ($parent_id > 0) { ?>
											<div class="row">
												<div class="col-md-12">
													<?php
													$html_breadcrumb = '';
													$data_breadcrumb = $this->drawing_management_model->breadcrum_array2($parent_id);
													foreach ($data_breadcrumb as $key => $value) {
														$html_breadcrumb = '<li class="breadcrumb-item"><a href="' . site_url('drawing_management?share_to_me=1&id=' . $value['id']) . '">' . $value['name'] . '</a></li>' . $html_breadcrumb;
													}
													?>
													<nav aria-label="breadcrumb">
														<ol class="breadcrumb">
															<?php echo drawing_htmldecode($html_breadcrumb); ?>
														</ol>
													</nav>
												</div>
											</div>
										<?php
										}
										if (drawing_check_share_permission($parent_id, 'upload_only')) { ?>
											<div class="file-form-group file-form">
												<?php echo form_open_multipart(site_url('drawing_management/upload_file/' . $parent_id . '/share_to_me'), array('id' => 'form_upload_file')); ?>
												<input type="file" id="files" name="file[]" multiple="">
												<div class="file-form-preview hide">
													<ul class="selectedFiles list-group list-group-flush mtop15" id="selectedFiles"></ul>
													<hr>
													<button class="btn btn-primary pull-right mright10 display-flex">
														<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-upload">
															<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
															<polyline points="17 8 12 3 7 8" />
															<line x1="12" y1="3" x2="12" y2="15" />
														</svg>
														<span class="mleft5 mtop2">
															<?php echo _l('dmg_upload_all'); ?>
														</span>
													</button>
												</div>
												<?php echo form_close(); ?>
											</div>
											<?php
										} else {
											$child_items = [];
											if ($parent_id == 0) {
												$child_items = $this->drawing_management_model->get_item('', 'id IN (' . $share_id . ')', 'name, id, dateadded, filetype, parent_id');
											} else {
												$child_items = $this->drawing_management_model->get_item('', 'parent_id = ' . $parent_id, 'name, id, dateadded, filetype, parent_id');
											}
											if ($parent_id == 0 || (is_numeric($parent_id) && $parent_id > 0 && drawing_dmg_get_file_type($parent_id) == 'folder')) {
												if (count($child_items)) {
													$this->load->view('file_managements/includes/item_list_share_to_me.php', ['child_items' => $child_items]);
												} else { ?>
													<div class="row mbot20">
														<div class="col-md-12">
															<h5 class="text-muted display-flex">
																<span class="text-warning">
																	<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-zap">
																		<polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2" />
																	</svg>
																</span>
																<span class="mtop3 mleft5"><?php echo _l('dmg_you_dont_have_any_files_or_folders_shared') . '.'; ?></span>
															</h5>
														</div>
													</div>
												<?php
												}
												if (drawing_check_share_permission($parent_id, 'editor')) { ?>
													<div class="file-form-group file-form">
														<?php echo form_open_multipart(site_url('drawing_management/upload_file/' . $parent_id . '/share_to_me'), array('id' => 'form_upload_file')); ?>
														<input type="file" id="files" name="file[]" multiple="">
														<div class="file-form-preview hide">
															<ul class="selectedFiles list-group list-group-flush mtop15" id="selectedFiles"></ul>
															<hr>
															<button class="btn btn-primary pull-right mright10 display-flex">
																<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-upload">
																	<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
																	<polyline points="17 8 12 3 7 8" />
																	<line x1="12" y1="3" x2="12" y2="15" />
																</svg>
																<span class="mleft5 mtop2">
																	<?php echo _l('dmg_upload_all'); ?>
																</span>
															</button>
														</div>
														<?php echo form_close(); ?>
													</div>
											<?php
												}
											} else {
												if ($edit == 1) {
													$this->load->view('file_managements/includes/file_edit.php');
												} else {
													$this->load->view('file_managements/includes/file_share_detail.php');
												}
											}
										}
									} elseif ($my_approval == 1) {
										if (count($approve_items)) {
											$this->load->view('file_managements/includes/item_list_approval.php', ['child_items' => $approve_items]);
										} else { ?>
											<div class="row mbot20">
												<div class="col-md-12">
													<h5 class="text-muted display-flex">
														<span class="text-warning">
															<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-zap">
																<polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2" />
															</svg>
														</span>
														<span class="mtop3 mleft5"><?php echo _l('dmg_you_dont_have_any_approval_requests') . '.'; ?></span>
													</h5>
												</div>
											</div>
										<?php
										}
									} elseif ($electronic_signing == 1) {
										if (count($approve_item_eids)) {
											$this->load->view('file_managements/includes/item_list_sign_approval.php', ['child_items' => $approve_item_eids]);
										} else { ?>
											<div class="row mbot20">
												<div class="col-md-12">
													<h5 class="text-muted display-flex">
														<span class="text-warning">
															<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-zap">
																<polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2" />
															</svg>
														</span>
														<span class="mtop3 mleft5"><?php echo _l('dmg_you_dont_have_any_approval_requests') . '.'; ?></span>
													</h5>
												</div>
											</div>
								<?php
										}
									}
								}
								?>
							</div>
						</div>
					</div>


				</div>
			</div>
		</div>
	</div>
</div>

<input type="hidden" name="check" value="">
<?php
require 'modules/purchase/assets/js/file_managements/file_management_js.php';
?>
		
<?php hooks()->do_action('app_admin_footer'); ?>