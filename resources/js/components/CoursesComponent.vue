<template>
<div id="project" class="container">
	<div class="row">
		<div class="col-12 col-sm-10 col-lg-6 mx-auto">
			<!-- Alert component -->
			<alert :type="type" :text="text"></alert>
			<!-- END Alert component -->
			<!-- Navbar -->
			<div class="form-inline my-2 my-sm-0">
				<button type="button" class="btn btn-light btn-sm" data-toggle="modal" @click="createModal"><img class="float-left" :src="'img/plus-circle.svg'" alt="Add Course" title="Add Course" role="img">&thinsp;New</button>
				<div class="input-group input-group-sm">
					  <div class="input-group-prepend">
						<span class="input-group-text" id="basic-addon1">
							<img class="float-left" :src="'img/search.svg'" alt="Search course" title="Search course" role="img">
						</span>
					  </div>
					  <input type="search" id="searchCourse" class="form-control mr-sm-2" placeholder="Search" aria-label="Search" aria-describedby="basic-addon1">
				</div>
			</div>
		<!-- END Navbar -->
			<!-- COURSES VUETABLE -->
			<vuetable ref="vuetable"
			 :api-mode="false"
			 :fields="fields"
			 :per-page="10"
			 :data-manager="dataManager"
				pagination-path="pagination"
				:no-data-template="'No records to show!'"
				@vuetable:pagination-data="onPaginationData">
				<div slot="actions" slot-scope="props">
					<button type="button" class="btn btn-primary btn-sm" @click="editModal(props.rowData)">Edit&thinsp;<img class="float-right" :src="'img/pencil.svg'" alt="Edit course" title="Edit course" role="img"></button>
				<button type="button" class="btn btn-danger btn-sm" @click="deleteModal(props.rowData)">Delete&thinsp;<img class="float-right" :src="'img/trash.svg'" alt="Delete course" title="Delete course" role="img"></button>
				</div>
			</vuetable>
			<div style="padding-top: 0.25em;">
				<vuetable-pagination-info ref="paginationInfo"
				:info-template="'Displaying {from} to {to} of {total} records'"
				:no-data-template="'No data'">
				</vuetable-pagination-info>
				<vuetable-pagination :css="css.pagination" ref="pagination" @vuetable-pagination:change-page="onChangePage">
				</vuetable-pagination>
			</div>
			<!-- END COURSES VUETABLE -->
			<!-- MODAL CREATE -->
			<div class="modal fade" id="createModalCenter" tabindex="-1" role="dialog" aria-labelledby="createModalCenterTitle" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="createModalCenterTitle">ADD COURSE</h5>
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
									<label for="name">Course Name:</label>
									<input type="text" v-model="course.name" class="form-control" id="name" placeholder="Enter a course name">
								</div>
								<div class="form-group">
									<label for="description">Course Description:</label>
									<input type="text" v-model="course.description" class="form-control" id="description" placeholder="Enter a course description">
								</div>
								<div class="input-group mb-3">
									<select v-model="select_index" @change="selectObjective" class="custom-select" id="inputGroupSelect">
										<option selected value="-1">Add other objective</option>
										<option v-for="(o,i) in objectives_select" v-bind:key="i" :value="i">{{o.name}}</option>
									</select>
									<div class="input-group-append">
										<label class="input-group-text" for="inputGroupSelect">Objectives</label>
									</div>
								</div>
								<!-- SHOW INPUTS-->
								<div v-if="select_index == -1" class="form-row align-items-center">
									<div class="form-group col-md-5">
										<input type="text" v-model="objective.name" class="form-control" id="inputName" placeholder="Enter a objective name">
									</div>
									<div class="form-group col-md-5">
										<input type="text" v-model="objective.description" class="form-control" id="inputDescription" placeholder="Enter a objective description">
									</div>
									<div class="form-group col-md-2">
										<input class="btn btn-primary" type="button" @click="addObjective()" name="Add" value="Add +">
									</div>
								</div>
								<!--END SHOW INPUTS-->
							<table class="table table-striped table-hover">
								<thead>
									<tr>
										<th scope="col">Name</th>
										<th scope="col">Description</th>
										<th scope="col">Action</th>
									</tr>
								</thead>
								<tbody>
									<tr v-for="(ob, i) in course.objectives" v-bind:key="i">
										<td>{{ob.name}}</td>
										<td>{{ob.description}}</td>
										<td>
											<button class="btn btn-danger btn-sm m-0" @click="removeObjective(i)"><img style="float:right;" :src="'img/trash.svg'" alt="Remove objective" title="Remove objective" role="img"></button>
										</td>
									</tr>
								</tbody>
							</table>
							</div>
							<!--END UI FORM INPUTS -->
						</div>
						<div class="modal-footer">
							<div class="btn-group" role="group" aria-label="Actions">
							<button type="button" class="btn btn-secondary btn-sm m-0" data-dismiss="modal">CANCEL&thinsp;<img style="float:right;" :src="'img/x.svg'" alt="Cancel" title="Cancel" role="img"></button>
							<button type="button" class="btn btn-primary btn-sm m-0" @click="saveCourse">SAVE&thinsp;<img style="float:right;" :src="'img/box-arrow-in-down.svg'" alt="Save" title="Save" role="img"></button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!--END MODAL CREATE -->
			<!-- MODAL EDIT COURSE -->
			<div class="modal fade" id="editModalCenter" tabindex="-1" role="dialog" aria-labelledby="editModalCenterTitle" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered" role="document">
					<div class="modal-content">
						<div class="modal-header bg-info text-white">
							<h5 class="modal-title" id="editModalCenterTitle">Edit Course</h5>
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
									<label for="name">Course Name:</label>
									<input type="text" v-model="course_edit.name" class="form-control" placeholder="Type up a course name">
								</div>
								<div class="form-group">
									<label for="description">Course Description:</label>
									<input type="text" v-model="course_edit.description" class="form-control" placeholder="Type up a course description">
								</div>
								<div class="input-group mb-3">
									<select v-model="select_index_edit" @change="selectObjectiveEdit" class="custom-select" id="inputGroupSelect">
										<option selected value="-1">Add other objective</option>
										<option v-for="(o,i) in objectives_select" v-bind:key="i" :value="i">{{o.name}}</option>
									</select>
									<div class="input-group-append">
										<label class="input-group-text" for="inputGroupSelect">Objectives</label>
									</div>
								</div>
								<!-- SHOW INPUTS-->
								<div v-if="select_index_edit == -1" class="form-row align-items-center">
									<div class="form-group col-md-5">
										<input type="text" v-model="objective_edit.name" class="form-control" id="inputName" placeholder="Enter a objective name">
									</div>
									<div class="form-group col-md-5">
										<input type="text" v-model="objective_edit.description" class="form-control" id="inputDescription" placeholder="Enter a objective description">
									</div>
									<div class="form-group col-md-2">
										<input class="btn btn-primary" type="button" @click="addObjectiveEdit()" name="Add" value="Add +">
									</div>
								</div>
								<!--END SHOW INPUTS-->
								<!-- Objectives Table -->
								<table class="table table-striped table-hover">
									<thead>
										<tr>
											<th scope="col">Name</th>
											<th scope="col">Description</th>
											<th scope="col">Action</th>
										</tr>
									</thead>
									<tbody>
										<tr v-for="(obj, ind) in course_edit.objectives" v-bind:key="ind">
											<td>{{obj.objective.name}}</td>
											<td>{{obj.objective.description}}</td>
											<td>
												<button class="btn btn-danger btn-sm m-0" @click="removeObjectiveEdit(ind)"><img style="float:right;" :src="'img/trash.svg'" alt="Remove objective" title="Remove objective" role="img"></button>
											</td>
										</tr>
									</tbody>
								</table>
								<!--End Objectives table-->
							</div>
							<!--END UI FORM INPUTS -->
						</div>
						<div class="modal-footer">
							<div class="btn-group" role="group" aria-label="Actions">
							<button type="button" class="btn btn-secondary btn-sm m-0" data-dismiss="modal">CANCEL&thinsp;<img style="float:right;" :src="'img/x.svg'" alt="Cancel" title="Cancel" role="img"></button>
							<button type="button" class="btn btn-primary btn-sm m-0" @click="updateCourse">SAVE&thinsp;<img style="float:right;" :src="'img/box-arrow-in-down.svg'" alt="Update" title="Update" role="img"></button>
							</div>
						</div>
					</div>
				</div>
			</div>
		<!-- END MODAL EDIT COURSE -->
		<!-- MODAL DELETE COURSE -->
		<div class="modal fade" id="deleteModalCenter" tabindex="-1" role="dialog" aria-labelledby="deleteModalCenterTitle" aria-hidden="true">
			<div class="modal-dialog modal-sm modal-dialog-centered " role="document">
				<div class="modal-content">
					<div class="modal-header bg-danger text-white">
						<h5 class="modal-title" id="deleteModalCenterTitle">Delete Course</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="bg-white py-3 px-3 shadow rounded">
								<p class="font-weight-bold">Do you want to delete the course?</p>
								<p class="font-italic"><strong>Name:</strong>&thinsp;{{name_delete}}</p>
						</div>
					</div>
					<div class="modal-footer">
						<div class="btn-group" role="group" aria-label="Actions">
						<button type="button" class="btn btn-secondary btn-sm m-0" data-dismiss="modal">CANCEL&thinsp;<img class="float-right" :src="'img/x.svg'" alt="Cancel" title="Cancel" role="img"></button>
						<button type="button" class="btn btn-danger btn-sm m-0" @click="deleteCourse">DELETE&thinsp;<img class="float-right" :src="'img/trash.svg'" alt="Delete" title="Delete" role="img"></button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- END MODAL DELETE COURSE -->
		</div>
	</div>
</div>
</template>
<script type="text/javascript">
import Alert from './Alert'
import Vuetable from 'vuetable-2/src/components/Vuetable'
import VuetablePagination from 'vuetable-2/src/components/VuetablePagination'
import VuetablePaginationInfo from 'vuetable-2/src/components/VuetablePaginationInfo'

export default {
	components: {
		Alert,
		Vuetable,
		VuetablePagination,
		VuetablePaginationInfo
		},
		// props: ['src'],
		data(){
			return{
				select_index: '',
				select_index_edit: null,
				course:{
					name: null,
					description: null,
					objectives: []
				},
				objective:{
					name: null,
					description: null,
					index: '-1,otherObjective',
				},
				course_edit: {},
				objective_edit:{
					name: null,
					description: null,
					index: '-1,otherObjective',
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
				objectives_select:[],
				courses: [],
				courses_full: []
			};
		},
		mounted() {
			let _this = this
			this.getAllCourses();
			this.getObjectives();
				//Search objective
			$('#searchCourse').keyup(function () {
				_this.searchRow($('#searchCourse').val());
			});
		},
		methods:{
			getObjectives: function(){
				let _this = this;
				let url = 'objectivecourses/get';
				axios.post(url)
				.then(function(response){
					if (response.data.length < 1) return;
					_this.objectives_select = response.data;
				})
				.catch(function(error){
					alert('Error: ' + error);
				});
			},
			getAllCourses: function(){
				var _this = this;
				var url = 'courses/get';
				axios.post(url)
				.then(function(response){
					if (response.data.length < 1) return;
					_this.courses = response.data;
					_this.courses_full = response.data;
				})
				.catch(function(error){
					alert('Error: ' + error);
				});
			},
			saveCourse: function(){
				var _this = this;
				var url ='courses/save';
				axios.post(url,{course:this.course})
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
						_this.getObjectives();
						_this.getAllCourses();
					}
				})
				.catch(function(error){
					alert('Error: ' + error);
				});
			},
			updateCourse: function(){
				var _this = this;
				var url ='courses/update';
				axios.post(url,{course:_this.course_edit})
				.then(function(response){
					if(response.data.error == true){
						//Clear all messages
						$("#error_messages_edit").empty();
						$.each(response.data.message, function(i, message){
							$("#error_messages_edit").append('<li>'+message+'</li>');
						});
						$("#show_message_edit").removeClass('d-none');
						$("#editModalCenter").modal('show');
						_this.getAllCourses();
						_this.getObjectives();
					}
					else{
						//Hide Modal
						_this.getAllCourses();
						_this.getObjectives();
						_this.type= response.data.title;
						_this.text= response.data.message;
						$("#editModalCenter").modal('hide');
					}
				})
				.catch(function(error){
				alert('Error: ' + error);
				});
			},
			deleteCourse: function(){
				var _this = this;
				var url ='courses/delete';
				axios.post(url,{id:_this.id_delete})
				.then(function(response){
					//Hide Modal
					_this.getAllCourses();
					_this.type= response.data.title;
					_this.text= response.data.message;
					$("#deleteModalCenter").modal('hide');
				})
				.catch(function(error){
					alert('Error: ' + error);
				});
			},
			searchRow: function(text){
			if (this.courses_full.length < 1) return;
			let data = this.courses_full;
			if (text) {
				let txt = new RegExp(text, 'i');
				//console.log(txt);
				data = _.filter(data, function (item) {
					return item.name.search(txt) >= 0
				})
				//console.log(data);
			}
				this.courses = data;
			},
			/*--- MODAL actions ---*/
			createModal: function(){
				let _this = this;
				_this.type = null;
				//clear all fields
				_this.resetFields();
				//clear all messages
				$("#error_messages").empty();
				$("#show_message").addClass('d-none');
				$('#createModalCenter').modal('show');
			},
			editModal: function(obj){
				this.course_edit = obj;
				this.type=null;
				obj = null;
				this.getAllCourses();
				this.getObjectives();
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
				_this.course.name = null;
				_this.course.description = null;
				_this.course.objectives = [];
				//  Object.keys(_this.course).forEach(key => {
				// 		_this.course[key] = ''
				// });
			},
			/*--- END modal actions ---*/
			/*---------- Table objectives actions ----------- */
			removeObjectiveEdit: function(i){
				this.course_edit.objectives.pop(i,1);
			},
			addObjectiveEdit: function(){
				let _this = this;
				this.objectives = {
					id: null,
					course_id: null,
					objective_id: null,
					other_objective_id: null,
					objective: {
						name: null,
						description: null,
						index: '-1,otherObjective'
					}
				};
				this.objectives.course_id = _this.course_edit.id;
				this.objectives.objective.name = _this.objective_edit.name;
				this.objectives.objective.description = _this.objective_edit.description;
				_this.course_edit.objectives.push(this.objectives);
				_this.objective_edit = {
					name: null,
					description: null,
					index: '-1,otherObjective'
				};
			},
			selectObjectiveEdit: function(){
				var flag = false, _this = this;
				this.objectives_edit = {
					id: null,
					course_id: null,
					objective_id: null,
					other_objective_id: null,
					objective: {
						name: null,
						description: null,
						index: null
					}
				};
				if(_this.select_index_edit != -1 && _this.select_index_edit != undefined){
					//retrieve a objective of the select -> index
					let objective = _this.objectives_select[_this.select_index_edit];
					this.objectives_edit.course_id = _this.course_edit.id;
					_this.objectives_edit.objective = objective;
					//map objectives
					_this.course_edit.objectives.map(function(item,i){
						if(item.objective == _this.objectives_edit.objective){
							flag = true;
						}
					});
					if(flag == false){
						//push Objective object
						_this.course_edit.objectives.push(_this.objectives_edit);
					}
				}
			},
			removeObjective:  function(i){
				this.course.objectives.pop(i,1);
			},
			addObjective: function(){
				let _this = this, obj;
				// To pass values from inputs
				obj = _this.objective;
				//empty Objective object
				_this.objective = {
					name: null,
					description: null,
					index: '-1,otherObjective'
				};
				_this.course.objectives.push(obj);
			},
			selectObjective: function(){
				var flag = false;
				let _this = this;
				if(_this.select_index != -1 && _this.select_index != undefined){
					if(_this.course.objectives.length < 1) {
						//push Objective object if empty
						_this.course.objectives.push(_this.objectives_select[_this.select_index]);
					}
					//map objectives distincts
					_this.course.objectives.map(function(item,i){
						if(item == _this.objectives_select[_this.select_index]){
							flag = true;
						}
					});
					if(flag == false){
						//push Objective object
						_this.course.objectives.push(_this.objectives_select[_this.select_index]);
					}
				}
			},
			/*-------- END Table objectives actions --------*/
			/*----------- Vuetable ----------*/
			onPaginationData(paginationData) {
					this.$refs.pagination.setPaginationData(paginationData);
					this.$refs.paginationInfo.setPaginationData(paginationData);
			},
			onChangePage(page) {
					this.$refs.vuetable.changePage(page);
			},
			dataManager(sortOrder, pagination) {
				if (this.courses.length < 1) return;
					let local = this.courses;

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
			courses: function(){
			var _this = this;
			_this.$refs.vuetable.refresh();
		}
	}
};
</script>
<style type="text/css">
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