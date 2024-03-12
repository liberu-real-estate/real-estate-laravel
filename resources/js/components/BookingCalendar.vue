<template>
  <div class="booking-calendar">
    <VCalendar v-model="selectedDate" :available-dates="availableDates" />
    <button @click="submitBookingRequest">Book Viewing</button>
  </div>
</template>

<script>
import { defineComponent, ref, onMounted } from 'vue';
import VCalendar from 'v-calendar';
import axios from 'axios';

export default defineComponent({
  name: 'BookingCalendar',
  components: {
    VCalendar
  },
  setup() {
    const selectedDate = ref(null);
    const availableDates = ref([]);

    const fetchAvailableDates = async () => {
      try {
        const response = await axios.get('/api/viewings/available-dates');
        availableDates.value = response.data;
      } catch (error) {
        console.error('Failed to fetch available dates:', error);
      }
    };

    const submitBookingRequest = async () => {
      if (!selectedDate.value) {
        alert('Please select a date for the viewing.');
        return;
      }

      try {
        const response = await axios.post('/api/viewings/book', {
          date: selectedDate.value
        });

        if (response.status === 201) {
          alert('Your viewing has been booked successfully.');
          selectedDate.value = null;
        } else {
          alert('Selected date is not available. Please choose another date.');
        }
      } catch (error) {
        console.error('Booking request failed:', error);
        alert('An error occurred while trying to book the viewing. Please try again.');
      }
    };

    onMounted(fetchAvailableDates);

    return {
      selectedDate,
      availableDates,
      submitBookingRequest
    };
  }
});
</script>

<style scoped>
.booking-calendar {
  display: flex;
  flex-direction: column;
  align-items: center;
}

button {
  margin-top: 20px;
  padding: 10px 20px;
  font-size: 16px;
  cursor: pointer;
}
</style>
