let elements = [];

let gproject = '';
let gid = 0;
let newname = '';

manage = ()=>{
	mng = {
		protection_word: "Delete",
	};

	mng.delete = (id)=>{
		$("#modal-3").modal();
		project = $('#'+id).find('#project_name').data('name');
		$("<p class='mt-3 text-center'>Seguro que quieres borrar este proyecto: <b>"+project+"</b>?</p>").appendTo(".modal-body");
		
		gproject = project;
		gid = id;

		$("#cancel").click(function(){
			$(".modal-body").find(".mt-3.text-center").remove();
		})
	}

	mng.save = (id)=>{
		project = $('#'+id).find('#project_name').data('name');

		$.ajax({
		  	url: "./api.php",
		  	data:{p:"save", r:project},
		  	type:'POST',
		  	dataType:'json',
		  	beforeSend:function(){
				$('<div id="loading" class="col-12"><div class="spinner-grow text-light" role="status">'+
  				'<span class="sr-only">Loading...</span>'+
			'</div></div>').prependTo('#'+id+' .card');
			}
		}).done(function(data){
			if(data.status=='success'){
				$('#'+id).find('.save_status').removeClass('unsaved');
				$('#'+id).find('.save_status').addClass('saved');
				$("#"+id).find(".save_status").attr('data-original-title', 'Salvado');
			}
			mng.send_toast(id, data.status, data.msg);




			//alert(data.msg);
		}).always(function() {
		    $('#'+id).find('#loading').remove();
		});
	}

	mng.edit = (id) => {
		project = $('#'+id).find('#project_name').data('name');

		$.ajax({
		  	url: "./api.php",
		  	data:{p:"open", a:'editor', r:project},
		  	type:'POST',
		  	dataType:'text',

		});
	}

	mng.rename = (id) => {
		$("#modal-4").modal();
		project = $('#'+id).find('#project_name').data('name');
		$("#modal-4").find('.modal-body b').text(project);

		gproject = project;
		gid = id;
	}

	mng.send_toast = (id, toast_class, text) => {
		$("<div>",{
			'class': 'toast '+toast_class,
			'role': 'alert',
			'aria-ive':'assertive',
			'aria-atomic':true,
			'id':"tost_"+id,
			'data-delay':2500,
		//	'data-autohide':false
		}).append(
			$("<div>",{
				'class': 'toast-header'
			}).append(
				$("<img>", {
					'src':'./favicon.ico',
					'class':'rounded mr-2'
				}),
				$("<strong>",{
					'class': 'mr-auto',
					'text': 'Cardinal System'
				}),
				$("<small>",{
					'class': '',
					'text': '11 mins ago'
				}),
				$("<button>",{
					'class': 'ml-2 mb-1 close',
					'type': 'button',
					'data-dismiss': 'toast',
					'data-label': 'Close'
				}).append(
					$("<span>",{
						'arian-hidden':true,
						'text':'x'
					})
				)
			),
			$("<div>",{
				'class': 'toast-body',
				'text': text
			})
		).prependTo('#toasts');

		$("#toastcontent").css('display', 'block');

		$("#tost_"+id).toast('show')
	}

	mng.findp = (id) => {
		project = $('#'+id).find('#project_name').data('name');

		$.ajax({
		  	url: "./api.php",
		  	data:{p:"openproject", r:project},
		  	type:'POST',
		  	dataType:'text',
		});
	}

	return mng;
}

searchs = () =>{
	serch = {};

	serch.find = () =>{
		target = $('#search').val();

		$.each(elements, function(i, v){
			$(v).remove();
		})

		$.each(elements, function(i, v){

			element = $(v).find('#project_name');

			if(element.length >0){
				var regex = new RegExp(target,'gi');
				var found = element.data('name').match(regex);
				if(found != null){
					$(v).appendTo('#projects_content');
					
					$('[data-role="project"]').click(function(){
						app.set_type($(this));
						return false;
					});
				}
			}
		});
	}

	serch.find();

	return serch;
}

App = () => {
	self = {};

	self.add_card = (id, data) => {

		$("<div>",{
			'class':'col-lg-2 col-md-6 col-sm-12 mb-3',
			'id':id,
			'data-role':'project_folder'
		}).append(
			$("<div>",{
				'class':'card'
			}).append(
				function(){
					if(data.tag.label != ''){
						return $("<div>",{
							'class':'tag '+data.tag.class,
						}).append(
							$("<div>",{
								'class':'tag_name',
								'text':data.tag.label
							})
						);
					}
				},
				$("<div>",{
					'class':'save_status unknown',
				}),
				$("<a>",{
					'href':'http://'+server+'/'+data.url,
					'target':data.name
				}).append(
					$("<div>",{
						'class': 'card-img-top',
						'style': 'background: url(\''+((data.have_icon)?'http://'+server+data.icon:'./assets/img/noicon.png')+'\')'
					}).append(
						$("<div>",{
							'class':'dropup dropleft'
						}).append(
							$("<div>",{
								'class':'dotosropsown dropdown-toggle',
								'data-toggle':'dropdown',
								'aria-haspopup':true,
								'aria-expande':false
							}),
							$("<div>",{
								'class':'dropdown-menu'
							}).append(
								$("<h6>",{
									'class':'dropdown-header',
									'text':'Tipo de proyecto'
								}),
								$("<span>",{
									'class':'dropdown-item',
									'data-role':'project',
									'data-project':'mine',
									'data-id':id,
									'text':'Mi Proyecto'
								}),
								$("<span>",{
									'class':'dropdown-item',
									'data-role':'project',
									'data-project':'coop',
									'data-id':id,
									'text':'Colaborativo'
								}),
								$("<span>",{
									'class':'dropdown-item',
									'data-role':'project',
									'data-project':'none',
									'data-id':id,
									'text':'No es mio'
								})
							)
						)
					)
				),
				$("<div>",{
					'class':'card-body'
				}).append(
					$("<div>", {
						'class':'row'
					}).append(
						$("<p>",{
							'id':'project_name',
							'data-name':data.name,
							'class':'card-title font-weight-bold col-10 pr-0'
						}).append(
							$("<a>",{
								'href':'http://'+server+'/'+data.name,
								'target':data.name,
								'text':data.name
							})
						),
						$("<p>",{
							'class':'card-title font-weight-bold col-2 p-0'
						}).append(
							function(){
								if(data.tag.class != 'core'){
									return $("<a>", {
										'href':"javascript:manage.rename('"+id+"')"
									}).append(
										$("<span>",{
											'class':'icon-edit ml-1'
										})
									)
								}
								
							}
						),
						$("<p>",{
							'class':'card-title font-weight-bold col-12 d-flex',
							'id':'actions'
						}).append(
							function(){
								if(data.tag.class != 'core'){
									return $("<a>", {
										'href':"javascript:manage.delete('"+id+"')"
									}).append(
										$("<span>",{
											'class':'icon-trash'
										})
									)
								}
								
							},
							$("<a>", {
								'href':"javascript:manage.save('"+id+"')",
								'id':'savep'
							}).append(
								$("<span>",{
									'class':'icon-save ml-1'
								})
							),
							$("<a>", {
								'href':"javascript:manage.edit('"+id+"')",
								'id':'editor'
							}).append(
								$("<span>",{
									'class':'icon-'+editor+' ml-1'
								})
							),
							$("<a>", {
								'href':"javascript:manage.findp('"+id+"')"
							}).append(
								$("<span>",{
									'class':'icon-folder ml-1'
								})
							)
						)
					)
				)
			)
		).hide().appendTo('#projects_content').fadeIn('slow');
		if(data.framework != ''){
			$("<div>",{
				'class':'recognize '+data.framework,
				'title':data.framework
			}).prependTo($('#'+id).find('.card-img-top'));
		}
		setTimeout(function(){
			if(sublime == 0){
				$('#'+id).find('a#editor').remove();
			}
			if(savep == 0){
				$('#'+id).find('a#savep').remove();
			}
		}, 100)
	}

	self.verify_backup = (id, data) => {
		$.post('./api.php', {p:'verify_backup',r:data.path}, function(data){
			data = JSON.parse(data);
			$("#"+id).find(".save_status").attr('class','save_status');
			clas = (data.status == 'ok')?'saved':'unsaved';
			title = (data.status == 'ok')?'Salvado':'Sin salvar';
			$("#"+id).find(".save_status").addClass(clas);

			$("#"+id).find(".save_status").attr('data-toggle', 'tooltip');
			$("#"+id).find(".save_status").attr('data-placement', 'bottom');
			$("#"+id).find(".save_status").attr('title', title);
			$("#"+id).find(".save_status").tooltip()
		});
	}

	self.get_projects = () => {
		$.post('./api.php', {p:'scan'}, function(data){
			data = JSON.parse(data);
			$.each(data, function(i,v){
				self.add_card(i, v);
			});
			$.each(data, function(i,v){
				self.verify_backup(i, v);
			});
			$('[data-role="project"]').click(function(){
				app.set_type($(this));
				return false;
			});
		});
	}

	self.set_type = (element) =>{
		type = element.data('project');
		id = element.data('id');
		name = $('#'+id).find('#project_name').data('name');
		
		$.ajax({
		  	url: "./api.php",
		  	data:{p:"set_type", project:name, type:type},
		  	type:'POST',
		  	dataType:'json',

		}).done(function(data){
			if(data.status == 'success'){
				tag = $('#'+id).find('.card .tag');
				if(tag.text().length>0){
					$('#'+id).find('.card .tag').remove();
				}
				if(type != 'none'){
					$tag = '<div class="tag '+data.tag.class+'">'+
							'<div class="tag_name">'+data.tag.label+'</div>'+
						'</div>';
					$($tag).appendTo($('#'+id).find('.card'));
				}
			}
		});
	}

	self.init = ()=>{
		manage = manage();
		search = searchs();
		self.get_projects();
		setTimeout(function(){elements = $('[data-role="project_folder"]');}, 100);
	}

	self.init();

	return self;
}

$('#newproject').click(function(){
	//var skeleton = null;
	$("#modal-2").modal();	
	
	$('#folders').empty();

	$.ajax({
		url: "./api.php",
		data:{p:"get_skeleton"},
		type:'POST',
		dataType:'json'
	  }).done(function(data){
		  $.each(data, function(i, v){
			$('#folders').append(
				$('<div>', {
					'class': 'skeleton_item',
					'data-element': i,
					'data-role': 'skeleton'
				}).append(
					$('<span>',{
						'class': 'skeleton_name',
						'text':v.name
					})
				)
			);
			if(v.framework!=''){
				$("<div>",{
					'class':'recognize '+v.framework,
					'title':v.framework
				}).prependTo($('[data-element="'+i+'"]'));
			}
		  });
	  });
})

$("#skeleton").on('click', function(){
	if($("#skeleton").is(":checked")){
		$('#folders').show('slow');
	}else{
		$('#folders').hide('slow');
	}
});

$('[id="create"]').click(function(){
	let proyect_name 		= $("#proyect_name").val();
	var skeleton 	= $("#skeleton").is(":checked");

	let skeleton_folder = $('#folders').find('.active').data('element');

	if(proyect_name.length > 0){
		$.ajax({
		  url: "./api.php",
		  data:{p:"newproject", skeleton:skeleton, project:proyect_name, skeleton_type:skeleton_folder},
		  type:'POST',
		  dataType:'json'
		}).done(function(data){
			console.log(data.status+' - '+data.msg);

			if(data.status == 'success'){
				$elements = $('[class="card"]');

				i = $elements.length +1;

				newProject = ''+
					'<div class="col-lg-2 col-md-6 col-sm-12 mb-3" id="'+i+'">'+
						'<div class="card">'+
								'<div class="tag">'+
									'<div class="tag_name">OWNER</div>'+
								'</div>'+
								
								'<div class="save_status unsaved"></div>'+

							'<a href="http://127.0.0.1/'+proyect_name+'" target="__blank">'+
								'<div class="card-img-top" style="background: url(\'./assets/img/noicon.png\');">'+
								''

								+'</div>'+
							'</a>'+

							'<div class="card-body">'+
								'<div class="row">'+
									'<p id="project_name" data-name="'+proyect_name+'" class="card-title font-weight-bold col-10 pr-0">'+
										'<a href="http://127.0.0.1/'+proyect_name+'" target="__blank">'+
											proyect_name+
										'</a>'+
									'</p>'+
									'<p class="card-title font-weight-bold col-2 p-0">'+
										'<a href="javascript:manage.rename(\''+i+'\')">'+
											'<span class="icon-edit ml-1"></span>'+
										'</a>'+
									'</p>'+
									'<p class="card-title font-weight-bold col-12 d-flex" id="actions">'+
										'<a href="javascript:manage.delete(\''+i+'\')">'+
											'<span class="icon-trash"></span>'+
										'</a>'+
										'<a href="javascript:manage.save(\''+i+'\')">'+
											'<span class="icon-save ml-1"></span>'+
										'</a>'+
										'<a href="javascript:manage.edit(\''+i+'\')">'+
											'<span class="icon-'+editor+' ml-1"></span>'+
										'</a>'+
										'<a href="javascript:manage.findp(\''+i+'\')">'+
											'<span class="icon-folder ml-1"></span>'+
										'</a>'+
									'</p>'+
								'</div>'+
							'</div>'+
						'</div>'+
					'</div>';

				$(newProject).appendTo('#projects_content');

				$('html, body').stop().animate({
					scrollTop: $('#'+i).offset().top
				}, 1000);

				setTimeout(function(){
					$('#'+i).find('.card').addClass('focused');
				},1000);
			}
		});
	}
	$("#modal-2").modal('toggle');
});

$('[id="delete"]').click(function(){
	$.ajax({
	  url: "./api.php",
	  data:{p:"delete", r:gproject},
	  type:'POST',
	  dataType:'json',

	}).done(function(data){
		if(data.status == "OK"){
			$('#'+gid).hide(400, function(){
				$('#'+gid).remove();
			});
		}
	});

	$("#modal-3").modal("toggle");
	$(".modal-body").find(".mt-3.text-center").remove();
});

$('[id="change"]').click(function(){
	var new_name = $("#new_name").val();
	$.ajax({
		  url: "./api.php",
		  data:{p:"rename", r:gproject, newname:new_name},
		  type:'POST',
		  dataType:'json'
	}).done(function(data){
		if(data.status=='success'){
			$('#'+gid).find('#project_name a').text(new_name);
			$('#'+gid).find('#project_name').data('name', new_name);
		}
		mng.send_toast(gid, data.status, data.msg);
	});
	$("#modal-4").modal('toggle');
});

$('[data-role="search"]').click(function(){
	search.find();
});
$('#search').keyup(function(){
	search.find();
});

$('#folders').on('click', '.skeleton_item', function(){
	$('[data-role="skeleton"]').removeClass('active');
	$(this).addClass('active');
})