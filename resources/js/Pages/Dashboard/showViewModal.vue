<script setup>
	import { ref} from 'vue';
	import TableCont from '@/Components/Common/TableCont.vue';
	import Money from '@/Components/Common/Money.vue';
	import DateShow from '@/Components/Common/DateShow.vue';
	import Header from '@/Components/Common/Header.vue';
	import DBService from '@/Service/Utils/DBService.js'

  	const { record_item } = defineProps(['record_item']);

  	const emit = defineEmits(['editDetails','close']);

  	function editDetails(rcd_id){
  		emit('editDetails', rcd_id);
  	}

	function deleteEntry(rcd_id){
	 	DBService.getData('/api/delete_data/' + rcd_id).then((data) => {
	 		bootbox.alert(data.message);
	        if (data.success) {
	        	emit('close');
	        }
	    });
	};


</script>
<template>
	<Header>
		<button  type="button" class="btn btn-primary mr-2" @click="editDetails(record_item.id)" >Shift Room</button>

		<button  type="button" class="btn btn-danger" @click="deleteEntry(record_item.id)" >Delete</button>
	</Header>
	<TableCont>
		<tbody>
			<tr>
				<th>Customer Name</th>
				<td>{{ record_item.customer_name}}</td>
			</tr>
			<tr>
				<th>Customer Email</th>
				<td>{{ record_item.customer_email}}</td>
			</tr>
			<tr>
				<th>Room Number</th>
				<td>{{ record_item.room_number}}</td>
			</tr>
			<tr>
				<th>Total Amount</th>
				<td><Money :amount="record_item.total_amount" /></td>
			</tr>
			<tr>
				<th>Check In</th>
				<td><DateShow :date="record_item.check_in" /></td>
			</tr>
			<tr>
				<th>Check Out</th>
				<td><DateShow :date="record_item.check_out" /></td>
			</tr>
		</tbody>	
	</TableCont>	
</template>