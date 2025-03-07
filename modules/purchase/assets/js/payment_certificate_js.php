<script>

$(function(){
});

calculate_payment_certificate();

function calculate_payment_certificate() {
	"use strict";
	var po_id = $('input[name="po_id"]').val();
	var payment_certificate_id = $('input[name="payment_certificate_id"]').val();
	
	if(po_id != '') {
		$.post(admin_url + 'purchase/get_po_contract_data/'+po_id+'/'+payment_certificate_id).done(function(response){
	        response = JSON.parse(response);
	        $('.po_name').html(response.po_name);

	        var po_contract_amount = response.po_contract_amount;
	        po_contract_amount = po_contract_amount.trim() != "" ? po_contract_amount : 0;
	        $('.po_contract_amount').html(format_money(po_contract_amount, true));

	        var po_previous = $('input[name="po_previous"]').val();
	        po_previous = po_previous.trim() != "" ? po_previous : 0;
	        $('.total_po_previous').html(format_money(po_previous, true));

	        var po_this_bill = $('input[name="po_this_bill"]').val();
	        po_this_bill = po_this_bill.trim() != "" ? po_this_bill : 0;
	        $('.total_po_this_bill').html(format_money(po_this_bill, true));

	        var po_comulative = parseFloat(po_previous) + parseFloat(po_this_bill);
	        $('.po_comulative').html(format_money(po_comulative, true));

	        var pay_cert_c1_1 = $('input[name="pay_cert_c1_1"]').val();
        	var mobilization_advance = $('select[name="mobilization_advance"]').val();
	        if(mobilization_advance) {
	        	mobilization_advance = mobilization_advance.replace('%', '');
	        	pay_cert_c1_1 = po_contract_amount * (mobilization_advance / 100);
	        	$('input[name="pay_cert_c1_1"]').val(pay_cert_c1_1);
	        } else {
	        	pay_cert_c1_1 = 0;
	        }

	        var pay_cert_c1_2 = $('input[name="pay_cert_c1_2"]').val();
        	var mobilization_advance = $('select[name="mobilization_advance"]').val();
	        if(mobilization_advance) {
	        	mobilization_advance = mobilization_advance.replace('%', '');
	        	pay_cert_c1_2 = po_previous * (mobilization_advance / 100);
	        	$('input[name="pay_cert_c1_2"]').val(pay_cert_c1_2);
	        } else {
	        	pay_cert_c1_2 = 0;
	        }

	        var pay_cert_c1_3 = $('input[name="pay_cert_c1_3"]').val();
        	var mobilization_advance = $('select[name="mobilization_advance"]').val();
	        if(mobilization_advance) {
	        	mobilization_advance = mobilization_advance.replace('%', '');
	        	pay_cert_c1_3 = po_this_bill * (mobilization_advance / 100);
	        	$('input[name="pay_cert_c1_3"]').val(pay_cert_c1_3);
	        } else {
	        	pay_cert_c1_3 = 0;
	        }

	        var pay_cert_c2_1 = $('input[name="pay_cert_c2_1"]').val();
	        pay_cert_c2_1 = pay_cert_c2_1.trim() != "" ? pay_cert_c2_1 : 0;
	        var net_advance_1 = parseFloat(pay_cert_c1_1) + parseFloat(pay_cert_c2_1);
	        $('.net_advance_1').html(format_money(net_advance_1, true));

	        var pay_cert_c2_2 = $('input[name="pay_cert_c2_2"]').val();
	        pay_cert_c2_2 = pay_cert_c2_2.trim() != "" ? pay_cert_c2_2 : 0;
	        var net_advance_2 = parseFloat(pay_cert_c1_2) + parseFloat(pay_cert_c2_2);
	        $('.net_advance_2').html(format_money(net_advance_2, true));

	        var pay_cert_c2_3 = $('input[name="pay_cert_c2_3"]').val();
	        pay_cert_c2_3 = pay_cert_c2_3.trim() != "" ? pay_cert_c2_3 : 0;
	        var net_advance_3 = parseFloat(pay_cert_c1_3) + parseFloat(pay_cert_c2_3);
	        $('.net_advance_3').html(format_money(net_advance_3, true));

	        var pay_cert_c1_4 = parseFloat(pay_cert_c1_2) + parseFloat(pay_cert_c1_3);
	        $('.pay_cert_c1_4').html(format_money(pay_cert_c1_4, true));
	        var pay_cert_c2_4 = parseFloat(pay_cert_c2_2) + parseFloat(pay_cert_c2_3);
	        $('.pay_cert_c2_4').html(format_money(pay_cert_c2_4, true));
	        var net_advance_4 = parseFloat(pay_cert_c1_4) + parseFloat(pay_cert_c2_4);
	        $('.net_advance_4').html(format_money(net_advance_4, true));

	        var sub_total_ac_1 = parseFloat(po_contract_amount) + parseFloat(net_advance_1);
	        $('.sub_total_ac_1').html(format_money(sub_total_ac_1, true));

	        var sub_total_ac_2 = parseFloat(po_previous) + parseFloat(net_advance_2);
	        $('.sub_total_ac_2').html(format_money(sub_total_ac_2, true));

	        var sub_total_ac_3 = parseFloat(po_this_bill) + parseFloat(net_advance_3);
	        $('.sub_total_ac_3').html(format_money(sub_total_ac_3, true));

	        var sub_total_ac_4 = parseFloat(sub_total_ac_2) + parseFloat(sub_total_ac_3);
	        $('.sub_total_ac_4').html(format_money(sub_total_ac_4, true));

	        var works_exe_a_1 = $('input[name="works_exe_a_1"]').val();
        	var works_executed_on_a = $('select[name="works_executed_on_a"]').val();
	        if(works_executed_on_a) {
	        	works_executed_on_a = works_executed_on_a.replace('%', '');
	        	works_exe_a_1 = sub_total_ac_1 * (works_executed_on_a / 100);
	        	$('input[name="works_exe_a_1"]').val(works_exe_a_1);
	        } else {
	        	works_exe_a_1 = 0;
	        }

		      var works_exe_a_2 = $('input[name="works_exe_a_2"]').val();
        	var works_executed_on_a = $('select[name="works_executed_on_a"]').val();
	        if(works_executed_on_a) {
	        	works_executed_on_a = works_executed_on_a.replace('%', '');
	        	works_exe_a_2 = sub_total_ac_2 * (works_executed_on_a / 100);
	        	$('input[name="works_exe_a_2"]').val(works_exe_a_2);
	        } else {
	        	works_exe_a_2 = 0;
	        }

		      var works_exe_a_3 = $('input[name="works_exe_a_3"]').val();
        	var works_executed_on_a = $('select[name="works_executed_on_a"]').val();
	        if(works_executed_on_a) {
	        	works_executed_on_a = works_executed_on_a.replace('%', '');
	        	works_exe_a_3 = sub_total_ac_3 * (works_executed_on_a / 100);
	        	$('input[name="works_exe_a_3"]').val(works_exe_a_3);
	        } else {
	        	works_exe_a_3 = 0;
	        }

		      var works_exe_a_4 = parseFloat(works_exe_a_2) + parseFloat(works_exe_a_3);
        	$('.works_exe_a_4').html(format_money(works_exe_a_4, true));

	        var ret_fund_1 = $('input[name="ret_fund_1"]').val();
	        ret_fund_1 = ret_fund_1.trim() != "" ? ret_fund_1 : 0;
	        var less_ret_1 = parseFloat(ret_fund_1) + parseFloat(works_exe_a_1);
	        $('.less_ret_1').html(format_money(less_ret_1, true));

	        var ret_fund_2 = $('input[name="ret_fund_2"]').val();
	        ret_fund_2 = ret_fund_2.trim() != "" ? ret_fund_2 : 0;
	        var less_ret_2 = parseFloat(ret_fund_2) + parseFloat(works_exe_a_2);
	        $('.less_ret_2').html(format_money(less_ret_2, true));

	        var ret_fund_3 = $('input[name="ret_fund_3"]').val();
	        ret_fund_3 = ret_fund_3.trim() != "" ? ret_fund_3 : 0;
	        var less_ret_3 = parseFloat(ret_fund_3) + parseFloat(works_exe_a_3);
	        $('.less_ret_3').html(format_money(less_ret_3, true));

	        var ret_fund_4 = parseFloat(ret_fund_2) + parseFloat(ret_fund_3);
	        $('.ret_fund_4').html(format_money(ret_fund_4, true));
	        var less_ret_4 = parseFloat(ret_fund_4) + parseFloat(works_exe_a_4);
	        $('.less_ret_4').html(format_money(less_ret_4, true));

	        var sub_t_de_1 = parseFloat(sub_total_ac_1) + parseFloat(less_ret_1);
	        $('.sub_t_de_1').html(format_money(sub_t_de_1, true));

	        var sub_t_de_2 = parseFloat(sub_total_ac_2) + parseFloat(less_ret_2);
	        $('.sub_t_de_2').html(format_money(sub_t_de_2, true));

	        var sub_t_de_3 = parseFloat(sub_total_ac_3) + parseFloat(less_ret_3);
	        $('.sub_t_de_3').html(format_money(sub_t_de_3, true));

	        var sub_t_de_4 = parseFloat(sub_t_de_2) + parseFloat(sub_t_de_3);
	        $('.sub_t_de_4').html(format_money(sub_t_de_4, true));

	        var less_1 = $('input[name="less_1"]').val();
	        less_1 = less_1.trim() != "" ? less_1 : 0;
	        var less_ded_1 = less_1;
	        $('.less_ded_1').html(format_money(less_ded_1, true));

	        var less_2 = $('input[name="less_2"]').val();
	        less_2 = less_2.trim() != "" ? less_2 : 0;
	        var less_ded_2 = less_2;
	        $('.less_ded_2').html(format_money(less_ded_2, true));

	        var less_3 = $('input[name="less_3"]').val();
	        less_3 = less_3.trim() != "" ? less_3 : 0;
	        var less_ded_3 = less_3;
	        $('.less_ded_3').html(format_money(less_ded_3, true));

	        var less_4 = parseFloat(less_2) + parseFloat(less_3);
	        $('.less_4').html(format_money(less_4, true));
	        var less_ded_4 = less_4;
	        $('.less_ded_4').html(format_money(less_4, true));

	        var less_ah_2 = $('input[name="less_ah_2"]').val();
	        less_ah_2 = less_ah_2.trim() != "" ? less_ah_2 : 0;
	        var less_ah_3 = $('input[name="less_ah_3"]').val();
	        less_ah_3 = less_ah_3.trim() != "" ? less_ah_3 : 0;
	        var less_ah_4 = parseFloat(less_ah_2) + parseFloat(less_ah_3);
	        $('.less_ah_4').html(format_money(less_ah_4, true));

	        var less_aht_2 = $('input[name="less_aht_2"]').val();
	        less_aht_2 = less_aht_2.trim() != "" ? less_aht_2 : 0;
	        var less_aht_3 = $('input[name="less_aht_3"]').val();
	        less_aht_3 = less_aht_3.trim() != "" ? less_aht_3 : 0;
	        var less_aht_4 = parseFloat(less_aht_2) + parseFloat(less_aht_3);
	        $('.less_aht_4').html(format_money(less_aht_4, true));

	        var sub_fg_1 = parseFloat(sub_t_de_1) + parseFloat(less_ded_1);
	        $('.sub_fg_1').html(format_money(sub_fg_1, true));

	        var sub_fg_2 = parseFloat(sub_t_de_2) + parseFloat(less_ded_2);
	        $('.sub_fg_2').html(format_money(sub_fg_2, true));

	        var sub_fg_3 = parseFloat(sub_t_de_3) + parseFloat(less_ded_3);
	        $('.sub_fg_3').html(format_money(sub_fg_3, true));

	        var sub_fg_4 = parseFloat(sub_fg_2) + parseFloat(sub_fg_3);
	        $('.sub_fg_4').html(format_money(sub_fg_4, true));

	        var cgst_on_a1 = po_contract_amount * 0.09;
	        $('.cgst_on_a1').html(format_money(cgst_on_a1, true));

	        var cgst_on_a2 = po_previous * 0.09;
	        $('.cgst_on_a2').html(format_money(cgst_on_a2, true));

	        var cgst_on_a3 = po_this_bill * 0.09;
	        $('.cgst_on_a3').html(format_money(cgst_on_a3, true));

	        var cgst_on_a4 = parseFloat(cgst_on_a2) + parseFloat(cgst_on_a3);
	        $('.cgst_on_a4').html(format_money(cgst_on_a4, true));

	        var sgst_on_a1 = po_contract_amount * 0.09;
	        $('.sgst_on_a1').html(format_money(sgst_on_a1, true));

	        var sgst_on_a2 = po_previous * 0.09;
	        $('.sgst_on_a2').html(format_money(sgst_on_a2, true));

	        var sgst_on_a3 = po_this_bill * 0.09;
	        $('.sgst_on_a3').html(format_money(sgst_on_a3, true));

	        var sgst_on_a4 = parseFloat(sgst_on_a2) + parseFloat(sgst_on_a3);
	        $('.sgst_on_a4').html(format_money(sgst_on_a4, true));

	        var labour_cess_1 = $('input[name="labour_cess_1"]').val();
        	var labour_cess = $('select[name="labour_cess"]').val();
	        if(labour_cess) {
	        	labour_cess = labour_cess.replace('%', '');
	        	labour_cess_1 = po_contract_amount * (labour_cess / 100);
	        	$('input[name="labour_cess_1"]').val(labour_cess_1);
	        } else {
	        	labour_cess_1 = 0;
	        }

	        var labour_cess_2 = $('input[name="labour_cess_2"]').val();
        	var labour_cess = $('select[name="labour_cess"]').val();
	        if(labour_cess) {
	        	labour_cess = labour_cess.replace('%', '');
	        	labour_cess_2 = po_previous * (labour_cess / 100);
	        	$('input[name="labour_cess_2"]').val(labour_cess_2);
	        } else {
	        	labour_cess_2 = 0;
	        }

	        var labour_cess_3 = $('input[name="labour_cess_3"]').val();
        	var labour_cess = $('select[name="labour_cess"]').val();
	        if(labour_cess) {
	        	labour_cess = labour_cess.replace('%', '');
	        	labour_cess_3 = po_this_bill * (labour_cess / 100);
	        	$('input[name="labour_cess_3"]').val(labour_cess_3);
	        } else {
	        	labour_cess_3 = 0;
	        }

	        var labour_cess_4 = parseFloat(labour_cess_2) + parseFloat(labour_cess_3);
        	$('.labour_cess_4').html(format_money(labour_cess_4, true));

	        var tot_app_tax_1 = parseFloat(cgst_on_a1) + parseFloat(sgst_on_a1) + parseFloat(labour_cess_1);
	        $('.tot_app_tax_1').html(format_money(tot_app_tax_1, true));

	        var tot_app_tax_2 = parseFloat(cgst_on_a2) + parseFloat(sgst_on_a2) + parseFloat(labour_cess_2);
	        $('.tot_app_tax_2').html(format_money(tot_app_tax_2, true));

	        var tot_app_tax_3 = parseFloat(cgst_on_a3) + parseFloat(sgst_on_a3) + parseFloat(labour_cess_3);
	        $('.tot_app_tax_3').html(format_money(tot_app_tax_3, true));

	        var tot_app_tax_4 = parseFloat(tot_app_tax_2) + parseFloat(tot_app_tax_3);
	        $('.tot_app_tax_4').html(format_money(tot_app_tax_4, true));

	        var amount_rec_1 = parseFloat(sub_fg_1) + parseFloat(tot_app_tax_1);
	        $('.amount_rec_1').html(format_money(amount_rec_1, true));

	        var amount_rec_2 = parseFloat(sub_fg_2) + parseFloat(tot_app_tax_2);
	        $('.amount_rec_2').html(format_money(amount_rec_2, true));

	        var amount_rec_3 = parseFloat(sub_fg_3) + parseFloat(tot_app_tax_3);
	        $('.amount_rec_3').html(format_money(amount_rec_3, true));

	        var amount_rec_4 = parseFloat(amount_rec_2) + parseFloat(amount_rec_3);
	        $('.amount_rec_4').html(format_money(amount_rec_4, true));
	    });
	}
}

function approve_payment_certificate_request(id){
  "use strict";
  payment_certificate_request_approval_status(id,2);
}
function deny_payment_certificate_request(id){
  "use strict";
   payment_certificate_request_approval_status(id,3);
}

function payment_certificate_request_approval_status(id, status){
  "use strict";
  var data = {};
  data.rel_id = id;
  data.rel_type = 'payment_certificate';
  data.approve = status;
  data.note = $('textarea[name="reason"]').val();
  $.post(admin_url + 'purchase/payment_certificate_request/' + id, data).done(function(response){
      response = JSON.parse(response); 
      if (response.success === true || response.success == 'true') {
          alert_float('success', response.message);
          window.location.reload();
      }
  });
}

function change_status_pay_cert(invoker,id){
  "use strict"; 
   $.post(admin_url+'purchase/change_status_pay_cert/'+invoker.value+'/'+id).done(function(reponse){
    reponse = JSON.parse(reponse);
    alert_float('success', reponse.result);
    window.location.reload();
  });
}

$("body").on('click', '.pay-cert-submit', function () { 
  var that = $(this);
  var form = that.parents('form._payment_transaction_form');
  form.submit();
});

</script>