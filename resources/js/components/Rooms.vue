<template>
  <v-container>
    <v-toolbar flat>
      <v-btn
        @click="dialog = true"
        color="primary"
        outlined
        large
        style="border-style: dashed;"
        >Add Room</v-btn
      >
    </v-toolbar>

    <v-data-table
      :search="search"
      :headers="headers_room"
      :items="rooms"
      item-key="id"
    >
      <template v-slot:item.code="{ item }">
        <v-btn @click="initPrint(item)" dark fab small color="purple">
          <v-icon>mdi-qrcode</v-icon>
        </v-btn>
        <qr-print
          v-if="printMode"
          :isStartPrint="isStartPrint"
          :qrCodeId="qrCodeId"
          :isShowQrCodeId="true"
          :headerSvg="qrHeaderSvg"
          :logoSvg="qrLogoSvg"
          qrSize="s"
          :title="qrtitle"
          :subtitle="'Date: ' + today"
          @endPrint="onEndPrint"
        >
        </qr-print>

        <!-- Dialog for QR Code Viewing -->
        <v-dialog v-model="dialogQR" max-width="500">
          <v-card class="pa-2">
            <v-card-title class="font-weight-bold"> Daily QR Code</v-card-title>
            <v-card-subtitle> Date: {{ today }} </v-card-subtitle>
            <v-card-text class="text-center">
              <qrcode
                :value="'cpsu-' + item.name + '-' + today"
                :options="{ width: 300 }"
              ></qrcode>
            </v-card-text>
            <v-card-actions>
              <v-btn
                @click="onStartPrint"
                dark
                small
                color="purple"
                class="ml-auto"
              >
                Print
                <v-icon right>mdi-printer</v-icon>
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </template>

      <template v-slot:item.action="{ item }">
        <v-btn icon color="red" dense @click="deleteRoom(item.id)">
          <v-icon>mdi-delete</v-icon>
        </v-btn>
      </template>
    </v-data-table>

    <!-- Dialog for adding Rooms -->
    <v-dialog v-model="dialog" max-width="500">
      <v-card>
        <v-card-title>
          Fill Info
        </v-card-title>
        <v-card-text>
          <v-text-field
            v-model="form.name"
            rounded
            small
            outlined
          ></v-text-field>
        </v-card-text>
        <div class="pa-3">
          <v-btn @click="saveRoom" block color="primary" class="mb-1"
            >Add</v-btn
          >
          <v-btn @click="dialog = false" block dark color="red">Cancel</v-btn>
        </div>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script>
export default {
  data() {
    return {
      rooms: [],
      dialog: false,
      dialogQR: false,
      form: new Form({
        name: "",
      }),

      //PRINT
      printMode: false,
      isStartPrint: false,
      qrCodeId: "",
      qrtitle: "",
      qrHeaderSvg: "<svg>...</svg>",
      qrLogoSvg:
        '<img src="img/logo.png" style="width: 50px; border: 2px solid green"/>',

      //   Data Table
      headers_room: [
        { text: "Room Id", value: "id" },
        { text: "Name", value: "name" },
        { text: "QR Code", value: "code" },
        { text: "Actions", sortable: false, value: "action", align: "center" },
      ],
    };
  },

  computed: {
    today() {
      let now = new Date();
      return moment(Date()).format("ll");
    },
  },

  methods: {
    initPrint(item) {
      let now = moment(Date()).format("ll");
      this.qrCodeId = "cpsu-" + item.name + "-" + now;
      this.qrtitle = "Room Name: " + item.name;
      this.printMode = true;
      this.dialogQR = true;
    },

    onStartPrint() {
      this.isStartPrint = true;
    },

    onEndPrint() {
      this.isStartPrint = false;
    },

    deleteRoom(id) {
      // console.log(id);
      Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
      }).then((result) => {
        if (result.value) {
          axios
            .delete("api/room/" + id)
            .then(() => {
              Swal.fire("Deleted!", "Room has been removed.", "success");
              this.getRooms();
            })
            .catch(() => {
              swal("Failed!", "Something went wrong.", "warning");
            });
        }
      });
    },

    saveRoom() {
      this.$Progress.start();
      axios
        .post("api/room", this.form)
        .then((response) => {
          // this.close(););
          console.log("addd");
          Swal.fire("Registered!", "New room added!", "success");
          this.form.reset();
          this.getRooms();
        })
        .catch((err) => {
          console.log(err);
        });
      this.$Progress.finish();
    },

    getRooms() {
      axios.get("api/room").then(({ data }) => {
        this.rooms = data.data;
      });
    },

    close() {},
  },

  created() {
    this.getRooms();
  },
};
</script>
