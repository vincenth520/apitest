<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>在线api生成工具 - 前端测试必备工具</title>
	<script src="https://unpkg.com/vue"></script>
	<script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
	<link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
</head>
<body>

<div class="container" id="app">
	<h1 class="text-center">在线api生成工具</h1>
		<div class="form-inline"><label>请求方式</label>:<select class="form-control" v-model="data.mode"><option v-for="mode in requestmode" v-bind:value="mode">{{mode}}</option></select></div>
		<h3>请求参数 <button class="btn btn-info" title="添加一行" @click="addKeys"><i class="fa fa-plus"></i></button></h3>
		<table class="table">
			<thead>
				<th>是否必填</th>
				<th>请求参数键名</th>
				<th>参数类型</th>
				<th>字段描述</th>
				<th>操作</th>
			</thead>
			<tbody>
				<tr v-for="(key,k) in data.keys">
					<td>
					    <input type="checkbox" v-model='key.must'>
				    </td>
					<td>
						<input type="text" v-model="key.name"  placeholder="请输入请求参数" class="form-control">
				    </td>
					<td>
						<select class="form-control" v-model="key.type">
						  <option value="int" >int</option>
						  <option value="string">string</option>
						</select>
				    </td>
				    <td>
				    	<input type="text" v-model="key.description"  placeholder="请输入字段描述" class="form-control">
				    </td>
				    <td>
				    	<button class="btn btn-danger" v-on:click="remove(k)">删除</button>
				    </td>
				</tr>
			</tbody>			
		</table>

		<h3>返回参数 <button class="btn btn-info" title="添加一行" @click="addValues"><i class="fa fa-plus"></i></button></h3>
		<table class="table">
			<thead>
				<th>返回参数键名</th>
				<th>参数类型</th>
				<th>字段描述</th>
				<th>操作</th>
			</thead>
			<tbody>
				<tr v-for="(key,k) in data.values">
					<td>
						<input type="text" @input="setvalue" v-model="key.name"  placeholder="请输入请求参数" class="form-control">
				    </td>
					<td>
						<select class="form-control" v-model="key.type">
						  <option value="int" >int</option>
						  <option value="string">string</option>
						</select>
				    </td>
				    <td>
				    	<input type="text" v-model="key.description"  placeholder="请输入字段描述" class="form-control">
				    </td>
				    <td>
				    	<button class="btn btn-info" title="添加一行" @click="addValues(k)"><i class="fa fa-plus"></i></button>
				    	<button class="btn btn-danger" v-on:click="removeValue(k)">删除</button>
				    </td>
				</tr>
			</tbody>			
		</table>


		<div class="form-inline"><label>返回格式</label>:<select class="form-control" v-model="data.callbacktype"><option v-for="mode in callbacktype" v-bind:value="mode">{{mode}}</option></select></div>

		<div class="form-inline"><textarea class="form-control" disabled="">{{JSON.stringify(data.value)}}</textarea></div>

</div>

<xmp theme="united" style="display:none;">

</xmp>
</body>
</html>

<script src="http://strapdownjs.com/v/0.2/strapdown.js"></script>


<script type="text/javascript">
	var app = new Vue({
	  el: '#app',
	  data: {
	  	mark:'',
	  	requestmode:['GET','POST','DELETE','PUT'],
	  	callbacktype:['json','xml','string','jsonp'],
	  	data:{
		  	mode:'GET',
		    keys: [
		    {name:'',description:'',must:false,type:'string'}
		    ],
		    values:[
		    {name:'',description:'',type:'string',son:[]}
		    ],
		    callbacktype:'json',
		    value:{}
	  	}
	  },
	  methods: {
	  	addKeys:function(){
	  		this.data.keys.push({name:'',description:'',must:false,type:'string'});
	  	},
	    remove: function (k) {
	      this.data.keys.splice(k,1);
	    },
	    addValues:function(k=''){
	    	this.data.values.push({name:'',description:'',son:[],type:'string'});
	    	if(!isNaN(k)){
	    		this.data.values[k].son.push(this.data.values.length-1);
	    	}
	    },
	    setvalue:function(){
	    	this.data.value = {};
	    	setvalueFunc();
	    },
	    removeValue:function(k){
	      this.data.values.splice(k,1);
	      this.data.value = {};
	      setvalueFunc();
	    }
	  }
	})

	function setvalueFunc(){		
		for (var node of app.data.values) {
		    for (var index of node.son) app.data.values[index].refrenced = true;
		}
		for (var node of app.data.values) {
		    if (!node.refrenced) resolve(node, app.data.value);
		}

		$.post('api.php',{data:app.data},function(data){
			console.log(data);
		})
	}	

	function resolve(node, parent) {
	    var object = parent[node.name] = {};
	    for (var index of node.son) resolve(app.data.values[index], object);
	}
	

</script>