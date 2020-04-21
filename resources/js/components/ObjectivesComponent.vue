<template>
<div id="project" class="container">
	<div class="row">
		<div class="col-12 col-sm-10 col-lg-6 mx-auto">
		<!-- Alert component -->
			<alert :type="type" :text="text"></alert>
		<!-- END Alert component -->
		<!-- Navbar -->
				<div class="form-inline my-2 my-sm-0">
					<button type="button" class="btn btn-light btn-sm" data-toggle="modal" @click="createModal"><img class="float-left" :src="'img/plus-circle.svg'" alt="Add Objective" title="Add Objective" role="img">&thinsp;New</button>
					<div class="input-group input-group-sm">
						  <div class="input-group-prepend">
							<span class="input-group-text" id="basic-addon1">
								<img class="float-left" :src="'img/search.svg'" alt="Search objective" title="Search objective" role="img">
							</span>
						  </div>
						  <input type="search" id="searchObjective" class="form-control mr-sm-2" placeholder="Search" aria-label="Search" aria-describedby="basic-addon1">
					</div>
				</div>
		<!-- END Navbar -->
		<!--VUETABLE OBJECTIVES-->
			<vuetable ref="vuetable"
			 :api-mode="false"
			 :fields="fields"
			 :per-page="10"
			 :data-manager="dataManager"
			pagination-path="pagination"
			:no-data-template="'No records to show!'"
			@vuetable:pagination-data="onPaginationData">
			<div slot="actions" slot-scope="props">
				<button type="button" class="btn btn-primary btn-sm" @click="editModal(props.rowData)">Edit&thinsp;<img class="float-right" :src="'img/pencil.svg'" alt="Edit objective" title="Edit objective" role="img"></button>
				<button type="button" class="btn btn-danger btn-sm" @click="deleteModal(props.rowData)">Delete&thinsp;<img class="float-right" :src="'img/trash.svg'" alt="Delete objective" title="Delete objective" role="img"></button>
			</div>
			</vuetable>
			<div style="padding-top:10px">
				<vuetable-pagination-info ref="paginationInfo"
				:info-template="'Displaying {from} to {to} of {total} records'"
				:no-data-template="'No data'">
				</vuetable-pagination-info>
				<vuetable-pagination :css="css.pagination" ref="pagination" @vuetable-pagination:change-page="onChangePage">
				</vuetable-pagination>
			</div>
		<!-- END VUETABLE OBJECTIVES -->
		<!-- MODAL CREATE OBJECTIVE-->
			<div class="modal fade" id="createModalCenter" tabindex="-1" role="dialog" aria-labelledby="createModalCenterTitle" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered" role="document">
					<div class="modal-content">
						<div class="modal-header bg-light text-dark">
							<h5 class="modal-title" id="createModalCenterTitle">Add Objective</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<!-- Alert error messages-->
							<div class="alert alert-danger d-none" id="show_message" role="alert">
								<ul class="m-0 p-2" id="error_messages"></ul>
							</div>
							<!-- END Alert error messages-->
							<!--UI FORM INPUTS -->
							<div class="bg-white py-3 px-3 shadow rounded">
								<div class="form-group">
									<label for="name">Name:</label>
									<input type="text" v-model="objective.name" class="form-control" id="name" placeholder="Type up a objective name">
								</div>
								<div class="form-group">
									<label for="description">Description:</label>
									<input type="text" v-model="objective.description" class="form-control" id="description" placeholder="Type up a objective description">
								</div>
							</div>
							<!--END UI FORM INPUTS -->
						</div>
						<div class="modal-footer">
							<div class="btn-group" role="group" aria-label="Actions">
							<button type="button" class="btn btn-secondary btn-sm m-0" data-dismiss="modal">CANCEL&thinsp;<img style="float:right;" :src="'img/x.svg'" alt="Cancel" title="Cancel" role="img"></button>
							<button type="button" class="btn btn-primary btn-sm m-0" @click="saveObjective">SAVE&thinsp;<img style="float:right;" :src="'img/box-arrow-in-down.svg'" alt="Save" title="Save" role="img"></button>
							</div>
						</div>
					</div>
				</div>
			</div>
		<!--END MODAL CREATE OBJECTIVE-->
		<!-- MODAL EDIT OBJECTIVE -->
			<div class="modal fade" id="editModalCenter" tabindex="-1" role="dialog" aria-labelledby="editModalCenterTitle" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered" role="document">
					<div class="modal-content">
						<div class="modal-header bg-info text-white">
							<h5 class="modal-title" id="editModalCenterTitle">Edit Objective</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<!-- Alert error messages-->
							<div class="alert alert-danger d-none" id="show_message_edit" role="alert">
								<ul class="m-0 p-2" id="error_messages_edit"></ul>
							</div>
							<!-- END Alert error messages-->
							<!--UI FORM INPUTS -->
							<div class="bg-white py-3 px-3 shadow rounded">
								<div class="form-group">
									<label for="name">Objective Name:</label>
									<input type="text" v-model="objective_edit.name" class="form-control" placeholder="Type up a objective name">
								</div>
								<div class="form-group">
									<label for="description">Objective Description:</label>
									<input type="text" v-model="objective_edit.description" class="form-control" placeholder="Type up a objective description">
								</div>
							</div>
							<!--END UI FORM INPUTS -->
						</div>
						<div class="modal-footer">
							<div class="btn-group" role="group" aria-label="Actions">
							<button type="button" class="btn btn-secondary btn-sm m-0" data-dismiss="modal">CANCEL&thinsp;<img style="float:right;" :src="'img/x.svg'" alt="Cancel" title="Cancel" role="img"></button>
							<button type="button" class="btn btn-primary btn-sm m-0" @click="updateObjective">SAVE&thinsp;<img style="float:right;" :src="'img/box-arrow-in-down.svg'" alt="Update" title="Update" role="img"></button>
							</div>
						</div>
					</div>
				</div>
			</div>
		<!-- END MODAL EDIT OBJECTIVE -->
		<!-- MODAL DELETE OBJECTIVE -->
		<div class="modal fade" id="deleteModalCenter" tabindex="-1" role="dialog" aria-labelledby="deleteModalCenterTitle" aria-hidden="true">
			<div class="modal-dialog modal-sm modal-dialog-centered " role="document">
				<div class="modal-content">
					<div class="modal-header bg-danger text-white">
						<h5 class="modal-title" id="deleteModalCenterTitle">Delete Objective</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="bg-white py-3 px-3 shadow rounded">
								<p class="font-weight-bold">Do you want to delete the record?</p>
								<p class="font-italic"><strong>Name:</strong>&thinsp;{{name_delete}}</p>
						</div>
					</div>
					<div class="modal-footer">
						<div class="btn-group" role="group" aria-label="Actions">
						<button type="button" class="btn btn-secondary btn-sm m-0" data-dismiss="modal">CANCEL&thinsp;<img class="float-right" :src="'img/x.svg'" alt="Cancel" title="Cancel" role="img"></button>
						<button type="button" class="btn btn-danger btn-sm m-0" @click="deleteObjective">DELETE&thinsp;<img class="float-right" :src="'img/trash.svg'" alt="Delete" title="Delete" role="img"></button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- END MODAL DELETE OBJECTIVE -->
		</div>
	</div>
</div>
</template>
<script type="text/javascript">

import Alert from './Alert'
import Vuetable from 'vuetable-2/src/components/Vuetable'
import VuetablePagination from 'vuetable-2/src/components/VuetablePagination'
import VuetablePaginationInfo from 'vuetable-2/src/components/VuetablePaginationInfo'

export default{
	components: {
		Alert,
		Vuetable,
		VuetablePagination,
		VuetablePaginationInfo
	},
	mounted(){
		var _this = this;
		_this.getAllObjectives();
		//Search objective
		$('#searchObjective').keyup(function () {
			_this.searchRow($('#searchObjective').val());
		});
	},
	data(){
		return{
			objective:{
				name: null,
				description: null
			},
			/*-- Vuetable Fields --*/
			fields:[
			{
				name:'name',
				sortField: 'name',
				title: 'Name'
			},
			{
				name:'description',
				sortField: 'description',
				title: 'Description'
			},
			{
				name: 'actions',
				title: 'Actions'
			}
			],
			css:{
				pagination: {
				wrapperClass: 'pagination',
				activeClass: 'active',
				disabledClass: 'disabled',
				pageClass: 'page-item',
				linkClass: 'page-link',
				paginationClass: 'pagination',
				paginationInfoClass: 'float-left',
				dropdownClass: 'form-control',
					icons: {
					  first: '',
					  prev: '',
					  next: '',
					  last: ''
					}
				},
				icons: {
				  first: 'glyphicon glyphicon-step-backward',
				  prev: 'glyphicon glyphicon-chevron-left',
				  next: 'glyphicon glyphicon-chevron-right',
				  last: 'glyphicon glyphicon-step-forward'
				}
			},
			/*-- End Vuetable Fields --*/
			type: null,
			text: null,
			id_delete: null,
			name_delete: null,
			objective_edit: {},
			objectives: [],
			objectives_full: []
		};
	},
	methods:{
		getAllObjectives: function(){
			var _this = this;
			var url ='objectives/get';
				axios.post(url)
				.then(function(response){
					if (response.data.length < 1) return;
					_this.objectives = response.data;
					_this.objectives_full = response.data;
				})
				.catch(function(error){
				alert('Error: ' + error);
			});
		},
		saveObjective: function(){
			var _this = this;
			var url ='objectives/save';
				axios.post(url,{ objective:this.objective})
				.then(function(response){
					if(response.data.error == true){
						//Clear all messages
						$("#error_messages").empty();
						$.each(response.data.message, function(i, message){
							$("#error_messages").append('<li>'+message+'</li>');
						});
						$("#show_message").removeClass('d-none');
						$("#createModalCenter").modal('show');
					}
					else{
						//Hide Modal
						_this.resetFields();
						$("#createModalCenter").modal('hide');
						_this.type= response.data.title;
						_this.text= response.data.message;
						_this.getAllObjectives();
					}
				})
				.catch(function(error){
				alert('Error: ' + error);
			});
		},
		updateObjective: function(){
			var _this = this;
			var url ='objectives/update';
				axios.post(url,{ objective:_this.objective_edit})
				.then(function(response){
					if(response.data.error == true){
						//Clear all messages
						$("#error_messages_edit").empty();
						$.each(response.data.message, function(i, message){
							$("#error_messages_edit").append('<li>'+message+'</li>');
						});
						$("#show_message_edit").removeClass('d-none');
						$("#editModalCenter").modal('show');
						_this.getAllObjectives();
					}
					else{
						//Hide Modal
						_this.type= response.data.title;
						_this.text= response.data.message;
						_this.getAllObjectives();
						$("#editModalCenter").modal('hide');
					}
				})
				.catch(function(error){
				alert('Error: ' + error);
			});
		},
		deleteObjective: function(){
			var _this = this;
			var url ='objectives/delete';
			axios.post(url,{id:_this.id_delete})
			.then(function(response){
				//Hide Modal
				_this.getAllObjectives();
				_this.type= response.data.title;
				_this.text= response.data.message;
				$("#deleteModalCenter").modal('hide');
			})
			.catch(function(error){
				alert('Error: ' + error);
			});
		},
		searchRow: function(text){
			if (this.objectives_full.length < 1) return;
			let data = this.objectives_full;
			if (text) {
				let txt = new RegExp(text, 'i');
				//console.log(txt);
				data = _.filter(data, function (item) {
					return item.name.search(txt) >= 0
				})
				//console.log(data);
			}
			this.objectives = data;
		},
		/*-- MODAL actions --*/
		createModal: function(){
			var _this = this;
			_this.type=null;
			$("#error_messages").empty();
			$("#show_message").addClass('d-none');
			$('#createModalCenter').modal('show');
		},
		editModal: function(obj){
			this.objective_edit = obj;
			this.type=null;
			obj = null;
			$("#error_messages_edit").empty();
			$("#show_message_edit").addClass('d-none');
			$('#editModalCenter').modal('show');
		},
		deleteModal: function(obj){
			var _this = this;
			_this.id_delete = obj.id;
			_this.name_delete = obj.name;
			obj = null;
			$("#deleteModalCenter").modal('show');
		 },
		resetFields: function(){
				var _this = this;
				_this.objective.name = null;
				_this.objective.description = null;
				//Iterate through each object field, key is name of the object field`
				// Object.keys(this.objective).forEach(function(key,index) {
				//   _this.objective[key] = '';
				// });
			},
		/*-- END MODAL actions --*/
		/* Vuetable */
			onPaginationData(paginationData) {
					this.$refs.pagination.setPaginationData(paginationData);
					this.$refs.paginationInfo.setPaginationData(paginationData);
			},
			onChangePage(page) {
					this.$refs.vuetable.changePage(page);
			},
			dataManager(sortOrder, pagination) {
				if (this.objectives.length < 1) return;

					let local = this.objectives;

			// sortOrder can be empty, so we have to check for that as well
			if (sortOrder.length > 0) {
				//console.log("orderBy:", sortOrder[0].sortField, sortOrder[0].direction);
				local = _.orderBy(
					local,
					sortOrder[0].sortField,
					sortOrder[0].direction
				);
			}

			pagination = this.$refs.vuetable.makePagination(
				local.length
				/*,this.perPage*/
			);
			//console.log('pagination:', pagination)
			let from = pagination.from - 1;
			let to = from + pagination.to;

			return {
				pagination: pagination,
				data: _.slice(local, from, to)
			};
			}
	 /*-- END Vuetable --*/
	},
	watch:{
			objectives: function(){
				var _this = this;
				_this.$refs.vuetable.refresh();
			}
		}
};
</script>
<style>
.pagination a.page-item {
  border: 1px solid lightgray;
  border-radius: 3px;
  padding: 5px 10px;
  margin-right: 2px;
}
.pagination a.page-item.active {
  color: white;
  background-color: #3c3c3c;
  border: 1px solid lightgray;
  border-radius: 3px;
  padding: 5px 10px;
  margin-right: 2px;
}
.pagination a.btn-nav {
  border: 1px solid lightgray;
  border-radius: 3px;
  padding: 5px 7px;
  margin-right: 2px;
}
.pagination a.btn-nav.disabled {
  color: lightgray;
  border: 1px solid lightgray;
  border-radius: 3px;
  padding: 5px 7px;
  margin-right: 2px;
  cursor: not-allowed;
}
</style>