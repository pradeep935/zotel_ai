<script setup>
	import {ref} from 'vue';
	import TableCont from '@/Components/Common/TableCont.vue';

	const {showItemDetails} = defineProps(['showItemDetails']);
</script>
<template>
	<div>
		<TableCont>
			<thead>
				<tr>
					<th>Item Name</th>
					<td>
						<span v-if="showItemDetails.item_name">{{showItemDetails.item_name}}</span>
						<span v-else>N/A</span>
					</td>
				</tr>
				<tr>
					<th>Category</th>
					<td>
						<span v-if="showItemDetails.category">{{showItemDetails.category}}</span>
						<span v-else>N/A</span>
					</td>
				</tr>
				<tr>
					<th>Brand</th>
					<td>
						<span v-if="showItemDetails.make">{{showItemDetails.make}}</span>
						<span v-else>N/A</span>
					</td>
				</tr>
				<tr>
					<th>Price</th>
					<td>
						<span v-if="showItemDetails.price">{{showItemDetails.price}}</span>
						<span v-else>N/A</span>
					</td>
				</tr>
				<tr>
					<th>GST %</th>
					<td>
						<span v-if="showItemDetails.gst">{{showItemDetails.gst}}</span>
						<span v-else>N/A</span>
					</td>
				</tr>
				<tr>
					<th>Unit</th>
					<td>
						<span v-if="showItemDetails.unit_name">{{showItemDetails.unit_name}}</span>
						<span v-else>N/A</span>
					</td>
				</tr>
				<tr>
					<th>Code</th>
					<td>
						<span v-if="showItemDetails.code">{{showItemDetails.code}}</span>
						<span v-else>N/A</span>
					</td>
				</tr>
			</thead>
		</TableCont>
	</div>	
</template>