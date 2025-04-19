<script setup>
    import { ref, onMounted, computed } from 'vue';
    import Header from '@/Components/Common/Header.vue';
    import Portlet from '@/Components/Common/Portlet.vue';
    import DBService from '@/Service/Utils/DBService.js';
    import dayjs from 'dayjs';
    import Modal from '@/Components/Common/Modal.vue';
    import AddBookingModal from '@/Pages/Dashboard/AddBookingModal.vue';
    import showViewModal from '@/Pages/Dashboard/showViewModal.vue';

    onMounted(fetchData);

    const showModal = ref(false);

    const processing = ref(true);

    const admin = ref(0);

    const data_points = ref([]);

    const user_list = ref([]);

    const filter = ref({ type: 'employee' });

    const currentMonth = ref(dayjs());

    const record_id = ref(0);

    const record_item = ref([]);

    const monthLabel = computed(() => currentMonth.value.format('MMMM YYYY'));


    const addBooking = () => {
        record_id.value = 0
        $('#show_modal').modal('show');
    }

    const closePopUp = () => {
        record_id.value = 0;
        $('#show_modal').modal('hide');
        fetchData();
    }
    const closeShowModal = () => {
        record_id.value = 0;
        $('#view_detail').modal('hide');
        fetchData();
    }

    function editBookingDetails(rcd_id) {
        $('#view_detail').modal('hide');
        record_id.value = rcd_id;
        $('#show_modal').modal('show');
    }   

    const handleBookingSubmit = (data) => {
        console.log('Booking submitted:', data)
    }


    function fetchData() {
        processing.value = true
        const start = currentMonth.value.startOf('month').format('YYYY-MM-DD')
        const end = currentMonth.value.endOf('month').format('YYYY-MM-DD')

        DBService.postData('/api/date-wise', {
            type: filter.value.type,
            date_start: start,
            date_end: end,
        }).then((data) => {
            if (data.success) {
                data_points.value = data.data_points
                user_list.value = data.user_list
                admin.value = data.admin
            }
            processing.value = false
        })
        .catch((error) => {
            console.error('Error fetching data:', error)
                processing.value = false
            })
        }

    const changeMonth = (direction) => {
        currentMonth.value = currentMonth.value.add(direction, 'month')
        fetchData()
    }

    const addWorklog = (label, type) => {
        alert(`Add worklog for ${label} (${type})`)
    }

    const showMeeting = (record) => {
      record_item.value = record;
      $('#view_detail').modal('show');
    }

</script>

<template>
  <Header title="Room Booking Dashboard" />

  <Portlet title="Room-wise Bookings">
    <!-- Controls -->
    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
      <div class="flex items-center gap-2">
        <button @click="changeMonth(-1)" class="bg-white border px-4 py-2 rounded-lg shadow hover:bg-gray-50">
          ← Previous
        </button>
        <h2 class="text-2xl font-bold text-gray-800">{{ monthLabel }}</h2>
        <button @click="changeMonth(1)" class="bg-white border px-4 py-2 rounded-lg shadow hover:bg-gray-50">
          Next →
        </button>
      </div>
      <button
        @click="addBooking"
        class="bg-gradient-to-r from-blue-500 to-blue-700 hover:from-blue-600 hover:to-blue-800 text-white px-5 py-2 rounded-xl shadow-lg transition-all duration-200"
      >
        + Add Booking
      </button>
    </div>

    <!-- Loader -->
    <div v-if="processing" class="text-center py-10 text-gray-600 text-lg">Loading data...</div>

    <!-- Data Table -->
    <div v-else class="overflow-x-auto rounded-lg border border-gray-200">
      <table class="min-w-full divide-y divide-gray-200 text-sm text-gray-800">
        <thead class="bg-gray-100 text-sm">
          <tr>
            <th class="text-left font-semibold px-4 py-3 sticky left-0 bg-gray-100 z-10 shadow-right">Room</th>
            <th
              v-for="data_point in data_points"
              :key="data_point.date"
              class="text-center font-semibold px-4 py-3 whitespace-nowrap"
            >
              <div class="flex flex-col items-center gap-0.5">
                <span>{{ data_point.label }}</span>
                <a
                  href="#"
                  @click.prevent="addWorklog(data_point.label, 'calendar')"
                  class="text-blue-500 hover:text-blue-700"
                >
                  <i class="fa fa-plus"></i>
                </a>
              </div>
            </th>
          </tr>
        </thead>

        <tbody>
          <tr v-for="item in user_list" :key="item.id" class="bg-white hover:bg-gray-50 border-t">
            <td class="px-4 py-3 font-semibold text-gray-900 sticky left-0 bg-white z-10 shadow-right">
              {{ item.name }}
            </td>
            <td
              v-for="data_point in data_points"
              :key="data_point.date"
              class="align-top px-4 py-2 min-w-[140px]"
            >
              <div
                v-for="record in item.records.filter(r => r.date === data_point.date)"
                :key="record.id"
                class="rounded-xl mb-2 p-3 shadow-sm hover:shadow-md transition-all duration-200 cursor-pointer"
                :style="{ background: filter.type === 'employee' ? record.clientColor : record.color }"
                @click="showMeeting(record)"
              >
                <div class="text-xs  text-center text-gray-800 mt-1">
                  <i class="fa fa-clock-o mr-1"></i>{{ record.customer_name }}
                </div>
                <div v-if="record.remark" class="text-xs italic text-gray-600 mt-1">
                  {{ record.remark }}
                </div>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </Portlet>

  <Modal id="show_modal" title="Add">
    <AddBookingModal :record_id="record_id" @close="closePopUp()" />
  </Modal>


    <Modal id="view_detail" title="Details">
        <showViewModal  :record_item="record_item"  @close="closeShowModal()" @editDetails="editBookingDetails"  />
    </Modal>
</template>

<style scoped>
.shadow-right {
  box-shadow: 2px 0 8px rgba(0, 0, 0, 0.05);
}
</style>
