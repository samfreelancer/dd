$(document).ready(function () {
   
    var all_price =parseFloat($('.cart-info-value span').html());
    $(document).on("click", ".add_to_cart", function () {
        var login = $('.add_to_cart').attr('login');
        var url = '';
        if (login == 'in') {
            url = "/account/default/addDomainToCart";
        } else if (login == 'out') {
            url = "/addDomainToCart";
        }
        var flag = $('.cart-info-count').html().split(' ')[0];
        var domain = $(this).attr('domain');
        var voice_domain = $(this).data("voice-domain");
        var unit_price = $(this).data("num");
        var domain_price = $(this).attr('price');
        var target = $(this);
        var price = $(this).parents('.product-item').find('.pi-price').html();
        var domain_row = $(this).parents('.product-item');
        var price = parseFloat(price.split("$")[1]);
        all_price = all_price + price;
        target.html('Verifing Availablity!');
        $.ajax({
            url: url,
            method: "POST",
            data: {domain: domain, domain_price: domain_price, quantity: 1, voice: voice_domain, unit_price: unit_price},
            dataType: "json",
            success: function (data) {
                flag++;
                if (data.status) {
                    if (flag > 1) {
                        $('.cart-info-count').html(flag + ' items');
                    } else {
                        $('.cart-info-count').html(flag + ' item');
                    }
                    $('.cart-info-value span').html(all_price.toFixed(2));
                    if (!$('.cart-content ul').hasClass('scroller')) {
                        $('.cart-content .text-right').before('<ul class="scroller"></ul>');
                    }
                    $('.scroller').append('<li><a href="javascript:void(0);"></a> <span class="cart-content-count">x 1</span>  <strong>' + data.data.domain + '</strong> <em>$' + data.data.revised_price + '</em><a href="javascript:void(0)" class="del-goods" domain="'+ data.data.domain +'" login="'+ login +'"><i class="fa fa-times"></i></a></li>');

                    target.removeClass('add_to_cart').addClass('disabled');
                    domain_row.addClass('domain_added');
                    target.html(data.message);
                    $('#cart').removeClass('disabled');
                } else {
                    target.removeClass('add_to_cart').addClass('disabled');
                    target.html(data.message);
                }
               countDomainPrice();

            },
            error: function (e) {
            }
        });
        return false;
    });
     countDomainPrice();
    $(document).on("click", ".del-goods", function (e) {
        var login = $(this).attr('login');
        var url = '';
        if (login == 'in') {
            url = "/account/default/removeFromCart";
        } else if (login == 'out') {
            url = "/removeFromCart";
        }
        var flag = $('.cart-info-count').html().split(' ')[0];
        var domain = $(this).attr('domain');
        var target = $(this);
        var price = $(this).parents('tr').find('.shopping-cart-total strong').text();
        var price = parseFloat(price.split("$")[1]);
        all_price = all_price-price;
        $.ajax({
            url: url,
            method: "POST",
            data: {domain: domain},
            dataType: "json", 
            success: function (data) {
            	if (data.status) {
            		flag--;
                    if (flag > 1) {
                        $('.cart-info-count').html(flag + ' items');
                    } else {
                        $('.cart-info-count').html(flag + ' item');
                    }
                    $('.cart-info-value span').html(all_price.toFixed(2));
                    //$('.shopping-total').find('.price').html(all_price.toFixed(2));
                    $('.scroller li').each(function () {
                        var li = $(this);
                        if ($(this).find('strong').html() == domain) {
                        	li.remove();
                        }
                    });
                    $('.shopping-cart-ref-no').each(function(){
                       if ($(this).html().trim() == domain){
                           $(this).parent().remove();
                       }
                    });
                    if ($('.shopping-total').find('.price').html() == '$0') {
                        $('.shopping-cart-data').find('div').remove();
                        $('.shopping-cart-data').append('<div class="shopping-cart-data clearfix"><p>Your shopping cart is empty!</p></div>');
                    }
                    countDomainPrice();
            	}
            }
        });
        return false;
    });

    $(document).on("change", ".selectpicker", function (e) {
        var data_index = $(this).attr('data-id-dropdown');
        var year_count = parseInt($(this).val());
        var allowed_years = [1,2,3,5,10];
        if (typeof year_count != 'undefined' && allowed_years.indexOf(year_count) != -1 ) {
        	var domain_name = $(this).parents('tr').children('td:eq(0)').text();
        	var login = $(this).attr('login');
            var url = "/updateQuantityCart";
            if (login == 'in') {
                url = "/account/default/updateQuantityCart";
            }
        	$.ajax({
                url: url,
                method: "POST",
                data: {domain: domain_name, quantity: year_count},
                dataType: "json",
                success: function (data) {
                    if (data.status) {
                    	window.location.reload();
                    } else {
                    	//console.log(data.message);
                    }
                }
        	});
        } else {
        	console.log("Item quantity not valid");
        }
        return false;
    });
    function countDomainPrice(){
        if ($('.domanTotalPrice').text()){
            $(".total_price_sum").html(getPrice());
            $('.shopping-total').find(getPrice());
        }else{
            $(".total_price_sum").html(calculatePrice());
            $('.shopping-total').find(calculatePrice());
        }
    }
    function calculateDomainPrice(){
         $(".total_price_sum").html(getPrice());
         $('.shopping-total').find(getPrice());
    }
    function getPrice(){
        var total = 0;
        $('.domanTotalPrice').each(function(){
            total += parseFloat($(this).text());
        });   
        
        return total.toFixed(2);
    }
    function calculatePrice(){
        var total = 0;
        $(".cart-content-wrapper .cart-content .scroller li").each(function(){
            var price = $(this).find('em').text();
            price = parseFloat(price.split("$")[1]);
            total +=price;
        });
        if (total >0){
            return total.toFixed(2);
        }
        return 0;
    }
    $(document).on("click","#button-payment-address, #button-payment-method", function(e){
    	var error = false;
    	var span = "<span class='text-danger'>Above field is required</span>";
    	var tt = ''; var tt1 = '';
    	if ($(this).attr('id') == "button-payment-address") {
    		tt = '#privacy_policy';
    		tt1 = '#payment-address-content';
    	} else if ($(this).attr('id') == "button-payment-method") {
    		tt = '#terms_condition';
    		tt1 = '#payment-method-content';
    	}
    	$(tt1).find("span.require").each(function(ev){
    		var parent = $(this).parents(':eq(2)');
    		var input = parent.find("input");
    		if (typeof input !== 'undefined' && input.val() == '') {
    			parent.find("span.text-danger").remove();
    			input.after(span);
    			error = true;
    		}
    	});
    	if(!$(tt).is(":checked")) {
    		error = true;
    		var pa = $(tt).parents(':eq(3)');
    		pa.find("span.text-danger").remove()
    		pa.append("<span class='text-danger'>Check the checkbox to proceed</span>");
    	}
    	if(error === true) {
    		e.stopImmediatePropagation();
    		e.stopPropagation();
    		e.preventDefault();
    		return false;
    	}
    });
    
    /*
     * Global ajax event
     */
    var $loading = $('#loading').hide();
    $(document).on("ajaxSend, ajaxStart", function(){
    	$loading.show();
    }).on("ajaxComplete, ajaxStop", function(){
    	$loading.hide();
	});
    
    /*
     * Show domain nameserver
     */
    $(document).on("click", ".domain_name", function(e){
    	var domain = $(this).closest("tr").find("td:nth-child(1)").text();
    	if (typeof domain != 'undefined' && domain != '' ) {
            url = "/account/default/getNameServer";
            $.ajax({
                url: url,
                method: "POST",
                data: {domain: domain},
                dataType: "json",
                success: function (data) {
                    if(data.status === true) {
                    	var html = '<table class="table table-responsive table-sm"><thead class="thead-inverse"><tr><th>Nameserver</th><th>Action</th></tr><thead><tbody>';
                    	if(typeof data.data != 'undefined' && data.data.length != '') {
                    		$.each(data.data, function(ind, ele){
                    			html += '<tr><td>'+ind+'</td><td><input type="button" class="btn btn-primary" name="del_nameserver" data-nameserver="'+ind+'" data-domain="'+domain+'" value="Delete"></td></tr>';
                    		});
                    	} else {
                    		html += '<tr><td colspan="2" class="text-center"><span class="text-danger">No record found</span></td></tr>'
                    	}
                    	html += '</tbody></table>';
                    	html += '<strong>Add Nameserver</strong> *';
                    	html += '<div class="row"><div class="col-sm-6"><input type="text" class="form-control" name="new_nameserver"></div><div class="col-sm-3"><input type="button" class="btn btn-primary" name="add_nameserver" id="add_nameserver" data-domain="'+domain+'" value="Add"></div></div>';
                    	var $modal = $("#commonModal");
                    	$modal.find(".modal-title").text("Current Namerservers");
                    	$modal.find(".modal-body").html(html);
                    	$modal.find(".modal-footer").addClass("hide");
                    	$modal.modal("show");
                    }
                }
        	});
        } else {
        	console.log("Invalid domain name");
        }
        return false;
    });
    
    /*
     * Show domain nameserver
     */
    $(document).on("click", ".domain_renew", function(e){
    	var $obj = $(this);
    	var domain = $obj.closest("tr").find("td:nth-child(1)").text();
    	if (typeof domain != 'undefined' && domain != '' ) {
            url = "/account/default/autoRenewal";
            $.ajax({
                url: url,
                method: "POST",
                data: {domain: domain},
                dataType: "json",
                success: function(data) {
                    if(data.status) {
                    	$obj.html(data.message);
                    	if (data.message == 'Auto Renew Off') {
                    		$obj.removeClass('text-success').addClass('text-danger').attr("title","Click to switch on");
                    	} else {
                    		$obj.removeClass('text-danger').addClass('text-success').attr("title","Click to switch off");
                    	}
                    	
                    }
                }
        	});
        } else {
        	console.log("Invalid domain name");
        }
        return false;
    });
    
    /*
     * Show dns
     */
    $(document).on("click", ".dns_name", function(e){
    	var domain = $(this).closest("tr").find("td:nth-child(1)").text();
    	if (typeof domain != 'undefined' && domain != '' ) {
            url = "/account/default/getDns";
            $.ajax({
                url: url,
                method: "POST",
                data: {domain: domain},
                dataType: "json",
                success: function (data) {
                    if(data.status === true) {
                    	var html = '<table class="table table-responsive table-sm"><thead class="thead-inverse"><tr><th>Name</th><th>Type</th><th>Content</th><th>Ttl</th><th>Priority</th><th>Action</th></tr><thead><tbody>';
                    	if(typeof data.data != 'undefined' && data.data.length != '') {
                    		$.each(data.data, function(ind, ele){
                    			if (typeof ele.name !== 'undefined') {
                    				html += '<tr><td>'+ele.name+'</td><td>'+ele.type+'</td><td>'+ele.content+'</td><td>'+ele.ttl+'</td><td>'+ele.priority+'</td><td><input type="button" class="btn btn-primary" name="del_dns" data-id="'+ele.record_id+'" value="Delete"></td></tr>';
                    			}
                    		});
                    	} else {
                    		html += '<tr><td colspan="6" class="text-center"><span class="text-danger">No record found</span></td></tr>'
                    	}
                    	html += '</tbody></table>';
                    	var $modal = $("#commonModal");
                    	$modal.find(".modal-title").text("Current DNS");
                    	$modal.find(".modal-body").html(html);
                    	$modal.find(".modal-footer .btn").text("Add New").addClass("add-new-dns-btn").data('domain',domain);
                    	$modal.modal("show");
                    }
                }
        	});
        } else {
        	console.log("Invalid domain name");
        }
        return false;
    });
    /*
     * Update Phone
     */
    $(document).on("click", ".phone_number", function(e){
    	var phone = $(this).data('phone');
    	var domain = $(this).closest("tr").find("td:nth-child(1)").text();
    	var $modal = $("#updatePhoneNumberModal");
    	$modal.find(".modal-title").text("Modify Phone Number of voice domain "+domain);
    	$modal.find("#phone_number").val(phone);
    	$modal.find("#domain").val(domain);
    	$modal.find(".modal-footer .btn").addClass("update-phone-number");
    	$modal.modal("show");
        return false;
    });
    $('#commonModal').on('hidden.bs.modal', function (event) {
    	var $modal = $(this);
    	$modal.find(".modal-title").text('');
    	$modal.find(".modal-body").text('');
    	$modal.find(".modal-footer").removeClass("hide").find(".btn").text("Save Changes");
    });
    $('#commonModal').on('shown.bs.modal', function (event) {
    	var $modal = $(this);
    	// delete a new nameserver
    	$modal.on("click", "input[name='del_nameserver']", function(ev){
    		var self = $(this);
    		var domain = self.data('domain');
    		self.prop('disabled',true).attr('disabled','disabled').val('Wait...');
    		var new_name = self.data('nameserver');
    		if (typeof new_name != 'undefined' && new_name != ''
    			&& typeof domain != 'undefined' && domain != '') {
    			$modal.find("input[name='new_nameserver']").parent().find("span.text-danger").remove();
    			url = "/account/default/deleteNameServer";
                $.ajax({
                    url: url,
                    method: "POST",
                    data: {nameserver: new_name, domain: domain},
                    dataType: "json",
                    async: false,
                    success: function(data) {
                        if(data.status === true) {
                        	self.closest("tr").remove();
                        } else {
                        	showErrorMsg($modal.find("input[name='new_nameserver']").parent(),"Some issue occured");
                        }
                    }
            	});
    		} else {
    			showErrorMsg($modal.find("input[name='new_nameserver']").parent(),"Invalid Information");
    		}
    		self.prop('disabled',false).removeAttr('disabled').val('Delete');
    	});
    	
    	// add a new nameserver
    	$modal.on("click", "#add_nameserver", function(ev){
    		ev.preventDefault();
    		ev.stopImmediatePropagation();
    		ev.stopPropagation();
    		var self = $(this);
    		var domain = self.data('domain');
    		var new_name = $modal.find("input[name='new_nameserver']").val();
    		$modal.find("span.text-danger").remove();
    		if (typeof new_name != 'undefined' && new_name != ''
    			&& typeof domain != 'undefined' && domain != '') {
    			url = "/account/default/addNewNameServer";
    			$modal.find("input[name='new_nameserver']").val('');
                $.ajax({
                    url: url,
                    method: "POST",
                    data: {nameserver: new_name, domain: domain},
                    dataType: "json",
                    async: false,
                    success: function(data) {
                        if(data.status === true) {
                        	$modal.find(".modal-body").find("tbody").append('<tr><td>'+new_name+'</td><td><input type="button" class="btn btn-primary" name="del_nameserver" data-nameserver="'+new_name+'" data-domain="'+domain+'" value="Delete"></td></tr>');
                        } else {
                        	showErrorMsg($modal.find("input[name='new_nameserver']").parent(),"Some problem occured");
                        }
                    }
            	});
    		} else {
    			showErrorMsg($modal.find("input[name='new_nameserver']").parent());
    		}
    		self.prop('disabled',false).removeAttr('disabled').val('Add');
    	});
    	
    	// add new dns form show
    	$modal.on("click",".add-new-dns-btn", function(e){
    		var domain = $(this).data('domain');
    		var $modal1 = $("#addDnsModal");
    		var $modal2 = $("#commonModal");
    		$modal2.modal("hide");
    		$modal1.find("form").find("input[name='domain']").remove();
    		$modal1.find("form").prepend("<input type='hidden' name='domain' value='"+domain+"' />");
    		$modal1.find("span.text-danger").remove();
    		resetForm($modal1.find('form'));
    		$modal1.modal("show");
    	});
    	
    	// delete a dns
    	$modal.on("click", "input[name='del_dns']", function(ev){
    		ev.preventDefault();
    		var self = $(this);
    		self.prop('disabled',true).attr('disabled','disabled').val('Wait...');
    		var self = $(this);
    		var record = self.data('id');
    		var domain = $modal.find(".add-new-dns-btn").data("domain");
    		if (typeof domain != 'undefined' && domain != ''
    			&& typeof record != 'undefined' && record != '') {
    			url = "/account/default/deleteDns";
                $.ajax({
                    url: url,
                    method: "POST",
                    data: {domain: domain, record: record},
                    dataType: "json",
                    async: false,
                    success: function(data) {
                        if(data.status === true) {
                        	self.closest("tr").remove();
                        } else {
                        	showErrorMsg($modal.find(".modal-footer"), "<strong>Some problem occured</strong>","prepend");
                        }
                    }
            	});
    		} else {
    			$modal.find("input[name='new_nameserver']").parent().append('<span class="text-danger">Invalid information</span>');
    		}
    		self.prop('disabled',false).removeAttr('disabled').val('Delete');
    	});
    });
    $('#addDnsModal').on('shown.bs.modal', function (event) {
    	var $modal = $(this);
    	$modal.find("button").prop("disabled", false).removeAttr("disabled");
    	$modal.on("click",".add-dns-go-back", function(e){
    		e.preventDefault();
    		e.stopImmediatePropagation();
    		e.stopPropagation();
    		$(this).prop("disabled", true).attr("disabled","disabled");
    		var domain = $modal.find("input[name='domain']").val();
    		$modal.modal("hide");
    		$(document).find(".dns_name").each(function(){
    			var dom = $(this).closest("tr").find("td:nth-child(1)").text();
    			if (domain == dom) {
    				$(this).trigger("click");
    				return false;
    			}
    		})
    	});
    	// add new dns form submit
    	$modal.on("click","button[type='submit']", function(ev){
    		var $form = $(this).closest("form");
    		$form.find("span.text-danger").remove();
    		$(this).prop('disabled', true).attr("disabled","disabled").text('Wait...');
    		if (validateForm($form) !== false) {
    			// form submittion validated
    			var formdata = false;
    		    if (window.FormData){
    		        formdata = new FormData($form[0]);
    		    }
    			url = "/account/default/addDns";
    			$.ajax({
                    url: url,
                    method: $form.attr("method"),
                    data: formdata ? formdata : $form.serialize(),
                    dataType: "json",
                    cache       : false,
                    contentType : false,
                    processData : false,
                    async: false,
                    success: function(data) {
                    	if(data.status === true) {
                    		$modal.find(".add-dns-go-back").trigger("click");
                        } else {
                        	showErrorMsg($modal.find(".modal-footer"), "<strong>Some problem occured</strong>","prepend");
                        }
                    }
            	});
    		} else {
    			// for validation failed
    		}
    		$(this).prop('disabled', false).removeAttr("disabled").text('Submit');
    		return false;
    	});
    	
    });
    $('#updatePhoneNumberModal').on('shown.bs.modal', function (event) {
    	var $modal = $(this);
    	$modal.find("button").prop("disabled", false).removeAttr("disabled");
    	$modal.find("#phone_number").on("keyup", function(){
    		var v = $(this).val();
    		if(v.length >= 3 && v.length <= 5) {
    			v = v.replace(/-/g,'');
    			$(this).val(v.substring(0,3)+'-'+v.substring(3));
    		}
    		if((v.length >= 7 && v.length <= 12)) {
    			v = v.replace(/-/g,'');
    			$(this).val(v.substring(0,3)+'-'+v.substring(3,6)+'-'+v.substring(6,10));
    		}
    		if(v.length > 12) {
    			v = v.replace(/-/g,'');
    			$(this).val(v.substring(0,3)+'-'+v.substring(3,6)+'-'+v.substring(6,10));
    		}
    	});
    	$modal.find("#phone_number").trigger('keyup');
    	// add new dns form submit
    	$modal.on("click","button[type='submit']", function(ev){
    		var $form = $(this).closest("form");
    		$form.find("span.text-danger").remove();
    		$(this).prop('disabled', true).attr("disabled","disabled").text('Wait...');
    		if (validateForm($form) !== false) {
    			// form submittion validated
    			var formdata = false;
    		    if (window.FormData){
    		        formdata = new FormData($form[0]);
    		    }
    			url = "/account/default/updatePhone";
    			$.ajax({
                    url: url,
                    method: $form.attr("method"),
                    data: formdata ? formdata : $form.serialize(),
                    dataType: "json",
                    cache       : false,
                    contentType : false,
                    processData : false,
                    async: true,
                    success: function(data) {
                    	if(data.status === true) {
                    		$modal.modal('hide');
                    		window.location.reload();
                    		//showErrorMsg($modal.find(".modal-footer"), "<strong>"+data.message+"</strong>","prepend");
                        } else {
                        	showErrorMsg($modal.find(".modal-footer"), "<strong>Some problem occured</strong>","prepend");
                        }
                    }
            	});
    		} else {
    			// for validation failed
    		}
    		$(this).prop('disabled', false).removeAttr("disabled").text('Update');
    		return false;
    	});
    });
    
    var validateForm = function($form) {
    	var is_error = false;
    	$form.find("input.required").each(function(){
    		var val = $.trim($(this).val());
    		var name = $(this).attr("name");
    		if ( typeof name != 'undefined' && val == '') {
    			is_error = true;
    			showErrorMsg($(this).parent());
    		}
    	});
    	$form.find("input.pattern").each(function(){
    		var val = $.trim($(this).val());
    		var pattern = $(this).attr("pattern");
    		if ( typeof pattern != 'undefined' && pattern != '') {
    			if (false === /^[2-9]\d{2}[\-]\d{3}[\-]\d{4}$/.test(val)) {
    				is_error = true;
        			showErrorMsg($(this).parent(),"Required format is 999-999-9999 and should not start with 1 or 0");
    			}
    		}
    	});
    	return !is_error;
    }
    
    var showErrorMsg = function($obj, msg, aen) {
    	if (msg == '' || msg == null) {
    		msg = "Above field is required";
    	}
    	$obj.find("span.text-danger").remove();
    	if(aen == 'prepend') {
    		$obj.prepend("<span class='text-danger'>"+msg+"</span>");
    	} else {
    		$obj.append("<span class='text-danger'>"+msg+"</span>");
    	}
    }
    
    var resetForm = function($form) {
    	$form.find("input[type='text']").val('');
    	$form.find("input[type='number']").val('');
    }
});