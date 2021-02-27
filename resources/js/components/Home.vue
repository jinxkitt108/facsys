<template>
    <v-container>
        <v-toolbar v-if="$gate.auth().type !== 'Admin'" flat>
            <div class="mx-auto">
                <v-btn @click="scan('in')" dark color="green" large class="mr-2"
                    ><v-icon left>mdi-clock</v-icon>Time In</v-btn
                >
                <v-btn @click="scan('out')" dark large
                    >Time Out<v-icon right>mdi-clock</v-icon></v-btn
                >
            </div>
        </v-toolbar>

        <!-- Dialog Qr Code -->
        <v-dialog v-model="dialog" max-width="500">
            <v-card class="pa-2">
                <v-card-title class="h3 font-weight-bold">
                    {{ attendance_form.type == "in" ? "Time In" : "Time Out" }}
                </v-card-title>
                <v-card-text>
                    <qrcode-stream
                        v-if="!camera_off"
                        @decode="onDecode"
                    ></qrcode-stream>
                </v-card-text>
                <v-card-actions>
                    <v-btn dark @click="close" class="ml-auto">Cancel</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-container>
            <v-row align="start">
                <v-col v-if="status == 'on-class'" cols="12" sm="6" md="8">
                    <v-card class="pa-4">
                        <v-card-title class="indigo darken-4">
                            <span class="font-weight-bold h4 text-white">CLASS DETAILS</span>
                            <v-chip dark color="blue" class="ml-auto">
                                <v-icon left>mdi-clock</v-icon>
                                Started: {{ class_details.in | logTime }}
                            </v-chip>
                        </v-card-title>
                        <v-card-content class="pa-2">
                            <v-list-item class="blue lighten-5">
                                <v-list-item-avatar size="100">
                                    <v-img
                                        :src="
                                            'img/profile_photos/' +
                                                $gate.auth().faculty_profile
                                                    .photo
                                        "
                                    ></v-img>
                                </v-list-item-avatar>
                                <v-list-item-content>
                                    <v-list-item-title class="font-weigth-bold"
                                        >Subject:
                                        {{
                                            class_details.schedule.subject_code
                                        }}</v-list-item-title
                                    >
                                    <v-list-item-title class="font-weigth-bold"
                                        >Course:
                                        {{
                                            class_details.schedule.course
                                        }}</v-list-item-title
                                    >
                                    <v-list-item-title
                                        class="font-weigth-bold"
                                        >{{
                                            class_details.schedule.year +
                                                "-" +
                                                class_details.schedule.section
                                        }}</v-list-item-title
                                    >
                                </v-list-item-content>
                            </v-list-item>
                        </v-card-content>
                    </v-card>
                </v-col>

                <v-col v-else cols="12" sm="6" md="8">
                    <div v-if="$gate.auth().type == 'Faculty'">
                        <v-sheet height="64">
                            <v-toolbar flat dark color="blue ligthen-4">
                                <v-btn fab text @click="$refs.calendar.prev()">
                                    <v-icon>mdi-chevron-left</v-icon>
                                </v-btn>
                                <v-spacer></v-spacer>
                                <v-btn fab text @click="nextMonth">
                                    <v-icon>mdi-chevron-right</v-icon>
                                </v-btn>
                            </v-toolbar>
                        </v-sheet>

                        <v-calendar
                            ref="calendar"
                            v-model="focus"
                            :now="today"
                            :events="events"
                            type="week"
                            color="primary"
                        >
                        </v-calendar>
                    </div>
                </v-col>
                <v-col
                    v-if="$gate.auth().type == 'Admin'"
                    cols="12"
                    sm="6"
                    md="8"
                >
                    <v-card>
                        <v-card-title>Monitoring</v-card-title>
                        <v-card-text>
                            <v-data-iterator
                                :search="search"
                                :items="attendances"
                            >
                                <template v-slot:header>
                                    <v-toolbar flat class="mb-1">
                                        <v-text-field
                                            type="time"
                                            class="mr-2"
                                            rounded
                                            outlined
                                            dense
                                        ></v-text-field>

                                        <v-text-field
                                            v-model="search"
                                            label="Search Room"
                                            rounded
                                            outlined
                                            dense
                                        ></v-text-field>
                                    </v-toolbar>
                                </template>
                                <template v-slot:default="props">
                                    <v-sheet
                                        v-for="item in props.items"
                                        :key="item.id"
                                        elevation="1"
                                        class="mb-3 pa-2"
                                    >
                                        <span class="font-weight-bold"
                                            >Name: {{ item.user.name }}</span
                                        >
                                        <span
                                            v-if="item.out == null"
                                            class="text-green float-right"
                                            >Class Started:
                                            {{ item.in | logTime }}</span
                                        >
                                        <span
                                            v-else
                                            class="text-red float-right"
                                            >Class Ended:
                                            {{ item.out | logTime }}</span
                                        >
                                        <v-list-item>
                                            <v-list-item-avatar size="100">
                                                <v-img
                                                    :src="
                                                        'img/profile_photos/' +
                                                            item.user
                                                                .faculty_profile
                                                                .photo
                                                    "
                                                ></v-img>
                                            </v-list-item-avatar>
                                            <v-list-item-content>
                                                <v-list-item-title
                                                    class="caption mb-1"
                                                    >Status:
                                                    <span
                                                        :class="
                                                            item.out == null
                                                                ? 'text-green'
                                                                : 'text-red'
                                                        "
                                                        class="overline"
                                                        >{{
                                                            item.out == null
                                                                ? "Ongoing"
                                                                : "Dismissed"
                                                        }}</span
                                                    ></v-list-item-title
                                                >

                                                <v-list-item-subtitle>
                                                    <v-icon
                                                        >mdi-home-circle-outline</v-icon
                                                    >
                                                    {{
                                                        item.room
                                                    }}</v-list-item-subtitle
                                                >
                                                <v-list-item-subtitle
                                                    >Course:
                                                    {{
                                                        item.schedule.course
                                                    }}</v-list-item-subtitle
                                                >
                                                <v-list-item-subtitle>{{
                                                    item.schedule.year +
                                                        "-" +
                                                        item.schedule.section
                                                }}</v-list-item-subtitle>
                                                <v-list-item-subtitle
                                                    class="text-black font-weight-bold"
                                                    >Subject:
                                                    {{
                                                        item.schedule
                                                            .subject_code
                                                    }}</v-list-item-subtitle
                                                >
                                            </v-list-item-content>
                                        </v-list-item>
                                    </v-sheet>
                                </template>
                            </v-data-iterator>
                        </v-card-text>
                    </v-card>
                </v-col>
                <v-col cols="12" sm="6" md="4">
                    <v-card>
                        <v-btn
                            @click="getLogs"
                            small
                            class="float-right ma-2"
                            color="primary"
                        >
                            Refresh
                            <v-icon right>mdi-refresh</v-icon>
                        </v-btn>
                        <v-card-title>Logs</v-card-title>
                        <v-card-text
                            style="height: 400px"
                            class="overflow-y-auto"
                        >
                            <div v-if="refresh" class="text-center pa-3">
                                <v-progress-circular
                                    :size="50"
                                    color="primary"
                                    indeterminate
                                ></v-progress-circular>
                            </div>
                            <v-list
                                v-else
                                v-for="log in logs"
                                :key="log.id"
                                dense
                            >
                                <v-list-item>
                                    <v-list-item-action>
                                        <v-icon
                                            :color="
                                                log.type == 'in'
                                                    ? 'green'
                                                    : 'red'
                                            "
                                            >mdi-account-clock</v-icon
                                        >
                                    </v-list-item-action>
                                    <v-list-item-content>
                                        <v-list-item-title
                                            class="text-uppercase font-weight-bold"
                                            v-text="log.user.name"
                                        ></v-list-item-title>
                                        <v-list-item-subtitle class="caption"
                                            >Time-<span class="text-capitalize"
                                                >{{ log.type }}:</span
                                            >
                                            {{
                                                log.created_at | logTime
                                            }}</v-list-item-subtitle
                                        >
                                        <v-list-item-subtitle class="caption"
                                            >Room:
                                            {{ log.room }}</v-list-item-subtitle
                                        >
                                        <v-list-item-subtitle
                                            class="overline font-weight-bold mt-1"
                                            >{{
                                                log.created_at | shortDate
                                            }}</v-list-item-subtitle
                                        >
                                    </v-list-item-content>
                                </v-list-item>
                                <v-divider></v-divider>
                            </v-list>
                            <!-- <div v-else v-for="log in logs" :key="log.id">
                                <div class="small font-weight-bold overline">
                                    {{ log.created_at | shortDate }}
                                </div>
                                <div class="text-justify ml-2">
                                    <span>{{ log.user.name }}</span> has
                                    timed-{{ log.type }} at
                                    <span class="font-weight-bold">{{
                                        log.created_at | logTime
                                    }}</span>
                                    in Room {{ log.room }}.
                                </div>
                                <v-divider inset></v-divider>
                            </div> -->
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </v-container>
</template>

<script>
export default {
    created() {
        this.getTodaysAttendance();
        this.getSchedule();
        this.getLogs();
        this.getStatus();
        this.getClassDetails();
    },

    mounted() {
        console.log("Component mounted.");
    },

    data() {
        return {
            //Monitoring Iterator
            search: "",

            class_details: "",
            status: "",
            attendances: [],
            camera_off: true,
            dialog: false,
            refresh: false,
            sched: null,
            events: [],
            logs: [],
            focus: "",
            date: new Date(),
            attendance_form: new Form({
                type: "",
                room: ""
            })
        };
    },

    methods: {
        getStatus() {
            axios.get("api/status").then(res => {
                this.status = res.data;
            });
        },

        getClassDetails() {
            axios.get("api/class_details").then(res => {
                this.class_details = res.data;
            });
        },

        getTodaysAttendance() {
            axios.get("api/attendance").then(res => {
                this.attendances = res.data;
            });
        },

        getLogs() {
            this.refresh = true;
            axios.get("api/logs").then(res => {
                this.logs = res.data;
                this.refresh = false;
            });
        },

        scan(type) {
            this.camera_off = false;
            this.dialog = true;
            this.attendance_form.type = type;
        },
        nextMonth() {
            this.$refs.calendar.next();
        },

        setToday() {
            this.date = new Date();
            this.focus = this.today;
        },

        onDecode(decodedString) {
            let now = moment(Date()).format("ll");
            const date = decodedString.split("-")[2];
            const room = decodedString.split("-")[1];
            if (date !== now) {
                Swal.fire("Alert!", "QRCode expired! " + date, "error");
            } else {
                this.attendance_form.room = room;
                this.$Progress.start();
                if (this.attendance_form.type == "out") {
                    this.attendance_form
                        .put("api/attendance")
                        .then(res => {
                            this.close();
                            if (res.data.type == "error") {
                                Swal.fire(
                                    "Alert!",
                                    res.data.message,
                                    "warning"
                                );
                            } else {
                                Swal.fire(
                                    "Successful!",
                                    "Timed out!",
                                    "success"
                                );
                                this.getLogs();
                                this.getStatus();
                            }
                            this.attendance_form.reset();
                        })
                        .catch(err => {
                            console.log(err);
                        });
                } else {
                    axios
                        .post("api/attendance", this.attendance_form)
                        .then(response => {
                            this.close();
                            if (response.data.type == "error") {
                                Swal.fire(
                                    "Alert!",
                                    response.data.message,
                                    "warning"
                                );
                            } else {
                                Swal.fire("Success!", "Timed in!", "success");
                                this.getLogs();
                                this.getStatus();
                                this.getClassDetails();
                            }
                            this.attendance_form.reset();
                        });
                }
            }

            this.$Progress.finish();
        },

        close() {
            this.dialog = false;
            this.camera_off = true;
        },

        getDateOfDay(day) {
            let date = this.date;
            let dayOfWeek = date.getDay();
            let days = day - dayOfWeek;
            let newDate = date.getDate() + days;
            return newDate;
        },

        getSchedule() {
            axios.get("api/schedule").then(res => {
                let sched = res.data;
                sched.forEach(item => {
                    let timeslot = item.timeslot;
                    timeslot.forEach(time => {
                        const days = [
                            "Sunday",
                            "Monday",
                            "Tuesday",
                            "Wednesday",
                            "Thursday",
                            "Friday",
                            "Saturday"
                        ];
                        let day = days.indexOf(time.day);
                        let date = this.date;
                        let dateOfDay = this.getDateOfDay(day);
                        let month =
                            date.getMonth() + 1 >= 10
                                ? date.getMonth() + 1
                                : "0" + (date.getMonth() + 1);
                        this.events.push({
                            name: item.subject_code,
                            start: String(
                                date.getFullYear() +
                                    "-" +
                                    month +
                                    "-" +
                                    dateOfDay +
                                    " " +
                                    time.start_time
                            ),
                            end: String(
                                date.getFullYear() +
                                    "-" +
                                    month +
                                    "-" +
                                    dateOfDay +
                                    " " +
                                    time.end_time
                            )
                        });
                    });
                });
            });
        }
    }
};
</script>
