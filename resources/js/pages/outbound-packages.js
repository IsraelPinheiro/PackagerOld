$(document).ready(function(){
	//Buttons
	//Button New
	$(".btn-outbounds-add").click(function(){
		$.get("/outbounds/create", function(data){
			$("body").append(data);
            $(".modal").modal("toggle");
		});
	});

	//Button Edit
	$(document).on("click", ".btn-outbounds-edit",function(event){
		$.get("/outbounds/"+$(event.target).data("id")+"/edit", function(data){
			$("body").append(data);
            $(".modal").modal("toggle");
		});
    });

    //Button Show
	$(document).on("click", ".btn-outbounds-show",function(event){
		$.get("/outbounds/"+$(event.target).data("id"), function(data){
			$("body").append(data);
            $(".modal").modal("toggle");
		});
	});

	//Button Store
	$(document).on("click", ".btn-package-send",function(){
		let files = document.getElementById("Files")
		let formData = new FormData()
		formData.append("Title", $("[name='Title']").val())
		formData.append("Description", $("[name='Description']").val())
		if("Recipient", $("[name='Recipient']").val()!=null){
			formData.append("Recipient", $("[name='Recipient']").val())
		}
		formData.append("DirectLinkStatus", $("[name='DirectLinkStatus']").val())
		formData.append("Password", $("[name='Password']").val())
		formData.append("ExpirationDateStatus", $("[name='ExpirationDateStatus']").val())
		formData.append("ExpirationDate", $("[name='ExpirationDate']").val())

		for (let file of files.files) {
			formData.append("files[]", file)
		}
		$.ajax({
			type:"POST",
			url:"outbounds/",
			data: formData,
			dataType: 'json',
			processData: false,
			contentType: false,
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			success: function(data){
				$('.modal').modal('hide');
				swal("Sucesso", data.message, "success").then(
					(value) => {
						location.reload();
					}
				);
			},
			error: function(data){
				var errors = data.responseJSON.errors;
				swal("Erro", errors[Object.keys(errors)[0]][0], "error")
			}
		});
	});

    //Button Update
	$(document).on("click", ".btn-outbounds-update",function(event){
        var id = $(event.target).data("id")
		var formData = $("#FormModal").serialize();
		$.ajax({
			type:"PUT",
			url:"outbounds/"+id,
			data: formData,
			dataType: 'json',
			success: function(data){
				$('.modal').modal('hide');
				swal("Sucesso", data.message, "success").then(
					(value) => {
						location.reload()
					}
				);
			},
			error: function(data){
				var errors = data.responseJSON.errors;
				swal("Erro", errors[Object.keys(errors)[0]][0], "error")
			}
		});
	});

	//Button Deletar
	$(document).on("click", ".btn-outbounds-del",function(event){
		swal({
			title: "Deseja excluir o pacote ?",
			icon: "warning",
			buttons: true,
			dangerMode: true,
		  })
		  .then((willDelete) => {if(willDelete){
				var id = $(event.target).data("id")
				$.ajax({
					url: "outbounds/"+id,
					type: 'POST',
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					data: {_method: 'delete'},
					success:function(data){
						$(event.target).closest("tr").remove()
						swal("Sucesso", data.message, "success").then(
							(value) => {
								location.reload();
							}
						);
					},
					error:function(data){
						var errors = data.responseJSON;
						swal("Erro", errors[Object.keys(errors)[0]], "error")
					}
				});
			}
		});
    });
    
    //Button Link
	$(document).on("click", ".btn-outbounds-link",function(event){
        const el = document.createElement('textarea');
		el.value = window.location.origin+"/package/"+$(event.target).data("key")
		document.body.appendChild(el);
		el.select();
		el.setSelectionRange(0, 99999); /*For mobile devices*/
		document.execCommand('copy');
		document.body.removeChild(el);
        swal("Sucesso", "Link Direto copiado para a Área de Transferência", "success")
	});

	$(document).on("change","#DirectLinkStatus",function(event){
		if($("#DirectLinkStatus").val() == "2"){
			$("#passwordContainer").removeClass("d-none")
			$("#Password").prop('required',true);

		}
		else{
			$("#passwordContainer").addClass("d-none")
			$("#Password").prop('required',false);
		}
	});
	$(document).on("change","#ExpirationDateStatus",function(event){
		if($("#ExpirationDateStatus").val() == "1"){
			$("#ExpirationDateContainer").removeClass("d-none")
			$("#ExpirationDate").prop('required',true);

		}
		else{
			$("#ExpirationDateContainer").addClass("d-none")
			$("#ExpirationDate").prop('required',false);
		}
	});
	$(document).on("change", "#Files", function(event){
		if($("#Files").val()){
			$("#FileName").text(event.target.files[0].name);
			$(".btn-remove").parent().removeClass("d-none"); 
		}
		else{
			$("#FileName").text("Escolha um arquivo");
			$(".btn-remove").parent().addClass("d-none");
		}
	});
	$(document).on("click", ".btn-remove",function(){
		$("#Files").val(null);
		$("#Files").change();
	});
	$(document).on("change", "#Files", function(event){
		if($("#Files").val()){
			if(event.target.files.length==1){
				$("#FileName").text(event.target.files[0].name);
			}
			else{
				$("#FileName").text(event.target.files.length+" Arquivos Selecionados");
			}
			$(".btn-remove").parent().removeClass("d-none"); 
		}
		else{
			$("#FileName").text("Escolha um arquivo");
			$(".btn-remove").parent().addClass("d-none");
		}
	});
	$(document).on("click", ".btn-remove",function(){
		$("#Files").val(null);
		$("#Files").change();
	});
});