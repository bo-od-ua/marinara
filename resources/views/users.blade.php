	<div class="easyui-layout" style="width:100%;height:100%;">
		<div data-options="region:'west'" style="width:40%;">
			<table id="users_list" class="easyui-datagrid" title=""
					data-options="
						url:'/api/users',  // 'storage.json'
						method:'get',
						fit:true,
						plain:1,
						border:false,
						pageSize:25,
						pageList:[25,50,100],
						pagination:true,
						rownumbers:true,
						fitColumns:true,
						singleSelect:true,
						toolbar:'#users_list-toolbar',
						onClickRow: function(node){},
						onLoadSuccess:function(){},
                        onClickRow: function(index,row){ usersEdit(row); },
                        onDblClickRow: function(index,row){ usersEdit(row); },
						loader: function(param, success, error){       // order sort rows page
                            let opts = $(this).datagrid('options');
                            if (!opts.url) return false;
                            $.ajax({
                                type: opts.method,
                                url: opts.url,
                                data: param,
                                dataType: 'json',
                                beforeSend: function (xhr) {
                                    let bearer= $('#userBearer').val();
                                    xhr.setRequestHeader('Authorization', 'Bearer '+ bearer);
                                },
                                success: function(data){ success(data); },
                                error: function(){ error.apply(this, arguments); }
                            });
                        },
                        loadFilter: function(data){
                            return {
                                total: data.data.total,
                                rows:  data.data.data
                            }
                        },
					">
				<thead>
					<tr>
						<th field="name" width="50" sortable="true">name</th>
						<th field="email" width="50" data-options="align:'center'" sortable="true">email</th>
					</tr>
				</thead>
			</table>
			<div id="users_list-toolbar" style="padding:3px">
				<a href="javascript:void(0)" class="easyui-linkbutton" onclick="usersEdit()" data-options="iconCls:'icon-add',plain:true">add</a>
<!--				<a href="javascript:void(0)" class="easyui-linkbutton" onclick="foo()" data-options="iconCls:'icon-save',plain:true,disabled:true">save</a> -->
				<a href="javascript:void(0)" class="easyui-linkbutton" onclick="foo()" data-options="iconCls:'icon-remove',plain:true,disabled:true">remove</a>

				<span style="float:right">
					<input id="users_search" class="search_input" data-controller="users" style="width:200px;"
					data-options="
						prompt: 'search in list',
						icons:[{
							iconCls:'icon-search',
							handler: function(e){
							    let controller= $(e.data.target).attr('data-controller');
							    search(controller, e.data.target.value);
							}
					}]">
				</span>
			</div>
		</div>
		<div data-options="region:'center'" style="width:60%;">
			<table id="users_item" class="easyui-propertygrid" data-options="
						url:'user_item.json',
						method:'get',
						showGroup:true,
						scrollbarSize:0,
						showHeader:false,
						fit:true,
						plain:1,
						border:false,
						toolbar:'#users_item-toolbar',
						onEndEdit: function(index,row){
							$('#users_item-button_save').linkbutton('enable');
							console.log(row);
						}
					" style="width:100%;">
			</table>
			<div id="users_item-toolbar" style="padding:3px">
				<a id="users_item-button_save" href="javascript:void(0)" class="easyui-linkbutton" onclick="foo()" data-options="iconCls:'icon-save',plain:true,disabled:true">save</a>
			</div>
		</div>
	</div>
