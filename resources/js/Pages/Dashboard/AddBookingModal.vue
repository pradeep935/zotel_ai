<script setup>

    import { ref, watch } from 'vue'
    import DBService from '@/Service/Utils/DBService'
    import SelectField from '@/Components/Common/SelectField.vue'
    import InputField from '@/Components/Common/InputField.vue'
    import Button2 from '@/Components/Common/Button2.vue';

    const emit = defineEmits(['close']);

    const { record_id } = defineProps(['record_id']);

    const processing = ref(false);

    const booking_record = ref([]);

    const formData = ref({});

    const roomOptions = ref([
      { label: 'Room 101', value: 'Room 101' },
      { label: 'Room 102', value: 'Room 102' },
      { label: 'Room 103', value: 'Room 103' },
    ]);

    watch(
        () => record_id,
        () => {
            editBookingDetails()
        }
    );

    function editBookingDetails(){
        if (record_id > 0) {
            DBService.getData('/api/edit_data/' + record_id).then((data) => {
                if (data.success) {
                    formData.value = data.booking_record;
                }
            })
        } else{
            
        }
    }

  function submit(){
    processing.value = true;
    DBService.postData('/api/add-booking/'+record_id,formData.value) .then((data) => {
      processing.value = false;
      bootbox.alert(data.message);
      if(data.success){
        emit('close');
        formData.value = { };
      } else {
        
      }
    });
  }

</script>
<template>
  <form @submit.prevent="submit()" >
    <InputField label="Customer Name" v-model="formData.customer_name" />
    <InputField label="Customer Email" v-model="formData.customer_email" />
    <SelectField label="Room Number" v-model="formData.room_number" :options="roomOptions" />
    <InputField  type="date" label="Check In" v-model="formData.check_in" v-if="!record_id" />
    <InputField  type="date" label="Check Out" v-model="formData.check_out" v-if="!record_id"/>
    <InputField   label="Total Amount" v-model="formData.total_amount" v-if="!record_id"/>
    <InputField   type="date" label="Change Room From" v-model="formData.change_date_from" v-if="record_id"/>


    <div class="form-footer">
      <Button2 :processing="processing" >Submit</Button2>
    </div>
    
  </form>
</template>
