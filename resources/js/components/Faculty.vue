<template>
    <v-container>
        <v-data-table
            :search="search"
            :headers="headers_faculty"
            :items="faculties"
            show-expand
            single-expand
            item-key="user_id"
            :expanded.sync="expanded"
            @update:expanded="loadSchedule"
            expand-icon="mdi-note"
        >
            <template v-slot:top>
                <v-toolbar flat>
                    <v-toolbar-title>
                        Faculty
                    </v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-text-field
                        v-model="search"
                        label="search"
                        prepend-icon="mdi-magnify"
                    ></v-text-field>
                    <v-spacer></v-spacer>
                    <v-btn color="primary" @click="dialog_faculty = true">
                        <v-icon left>mdi-plus</v-icon>Faculty
                    </v-btn>
                </v-toolbar>
            </template>

            <!-- Modify Table Contents -->
            <template v-slot:item.photo="{ item }">
                <v-avatar size="40">
                    <v-img :src="'img/profile_photos/' + item.photo"></v-img>
                </v-avatar>
            </template>

            <template v-slot:item.action="{ item }">
                <v-btn @click="editFaculty(item)" icon color="green">
                    <v-icon>mdi-pen</v-icon>
                </v-btn>
                |
                <v-btn icon color="red" @click="deleteFaculty(item.user_id)">
                    <v-icon>mdi-delete</v-icon>
                </v-btn>
            </template>

            <!-- Expanded Items -->
            <template v-slot:expanded-item="{ headers, item }">
                <td
                    :colspan="headers.length"
                    @load="loadSchedule(item.user_id)"
                >
                    <v-card flat tile>
                        <v-toolbar flat dense>
                            <v-btn text @click="openSched(item)">
                                <v-icon left>mdi-plus</v-icon>New
                            </v-btn>
                            <v-btn icon @click="loadSchedule()"
                                ><v-icon>mdi-refresh</v-icon></v-btn
                            >
                        </v-toolbar>
                        <v-card-text>
                            <v-data-table
                                v-if="sched_loaded"
                                :items="schedules"
                                :headers="headers_schedule"
                            >
                                <template v-slot:item.class="{ item }">
                                    {{ item.year }}-{{ item.section }}
                                </template>
                                <template v-slot:item.timeslot="{ item }">
                                    <v-list-item>
                                        <v-list-item-content
                                            v-for="(slot, i) in item.timeslot"
                                            :key="i"
                                        >
                                            <v-list-item-title>
                                                {{ slot.room }}
                                            </v-list-item-title>
                                            <v-list-item-subtitle>
                                                {{ slot.day }}
                                            </v-list-item-subtitle>
                                            <v-list-item-subtitle>
                                                {{ slot.start_time }}-{{
                                                    slot.end_time
                                                }}
                                            </v-list-item-subtitle>
                                        </v-list-item-content>
                                    </v-list-item>
                                </template>
                                <template v-slot:item.action="{ item }">
                                    <v-btn
                                        @click="editSchedule(item)"
                                        dense
                                        icon
                                        color="green"
                                    >
                                        <v-icon>mdi-pen</v-icon>
                                    </v-btn>

                                    <v-btn
                                        icon
                                        color="red"
                                        dense
                                        @click="deleteSchedule(item.id)"
                                    >
                                        <v-icon>mdi-delete</v-icon>
                                    </v-btn>
                                </template>
                            </v-data-table>
                        </v-card-text>
                    </v-card>
                </td></template
            >
        </v-data-table>

        <!-- Faculty Add/Edit Faculty -->
        <v-dialog v-model="dialog_faculty" width="650">
            <v-card>
                <v-toolbar color="indigo" dark dense class="mb-2">
                    <v-toolbar-title>{{
                        editmode ? "Update Faculty" : "New Faculty"
                    }}</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-btn text @click="close">
                        Close
                    </v-btn>
                </v-toolbar>

                <v-card-text>
                    <v-row>
                        <v-col cols="12" md="6">
                            <v-text-field
                                label="Faculty Id"
                                v-model="faculty_form.faculty_id"
                                dense
                                outlined
                            ></v-text-field>
                            <v-text-field
                                label="Name"
                                v-model="faculty_form.name"
                                dense
                                outlined
                            ></v-text-field>
                            <v-text-field
                                label="Birthdate"
                                type="date"
                                v-model="faculty_form.birthdate"
                                dense
                                outlined
                            ></v-text-field>
                            <v-text-field
                                label="Email"
                                type="email"
                                v-model="faculty_form.email"
                                dense
                                outlined
                            ></v-text-field>
                            <v-text-field
                                label="Address"
                                v-model="faculty_form.address"
                                dense
                                outlined
                            ></v-text-field>
                            <v-select
                                label="Degree"
                                v-model="faculty_form.degree"
                                :items="[
                                    'Ph.D',
                                    'Masters Degree',
                                    'Bachelors Degree'
                                ]"
                                dense
                                outlined
                            ></v-select>
                        </v-col>

                        <v-col cols="12" md="6">
                            <v-text-field
                                label="Employment Status"
                                v-model="faculty_form.employment_status"
                                hint="Ex. Permanent,Temporary,Part-Time"
                                dense
                                outlined
                            ></v-text-field>
                            <v-select
                                label="Department"
                                v-model="faculty_form.department"
                                :items="[
                                    'College of Computer Studies',
                                    'College of Business Management',
                                    'College of Teacher Education',
                                    'College of Criminal Justice Education',
                                    'College of Agrriculture  & Forestry'
                                ]"
                                dense
                                outlined
                            ></v-select>
                            <v-text-field
                                label="Position"
                                v-model="faculty_form.position"
                                dense
                                outlined
                            ></v-text-field>
                            <v-text-field
                                label="Username"
                                v-model="faculty_form.username"
                                dense
                                outlined
                            ></v-text-field>
                            <v-text-field
                                :append-icon="
                                    show_password ? 'mdi-eye' : 'mdi-eye-off'
                                "
                                :type="show_password ? 'text' : 'password'"
                                @click:append="show_password = !show_password"
                                label="Password"
                                hint="At least 8 characters"
                                v-model="faculty_form.password"
                                dense
                                outlined
                            ></v-text-field>
                            <v-file-input
                                accept="image/*"
                                label="File input"
                                outlined
                                dense
                                @change="fileChange"
                            ></v-file-input>
                            <v-avatar v-if="faculty_form.photo" size="200" tile>
                                <v-img :src="faculty_form.photo"></v-img>
                            </v-avatar>
                        </v-col>
                    </v-row>
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="red" text @click="close">Cancel</v-btn>
                    <v-btn
                        color="success"
                        text
                        @click="editmode ? updateFaculty() : addFaculty()"
                        >{{ editmode ? "Update" : "Save" }}</v-btn
                    >
                </v-card-actions>
            </v-card>
        </v-dialog>

        <!-- Faculty Add/Edit Schedule -->
        <v-dialog
            v-model="dialog_schedule"
            fullscreen
            hide-overlay
            transition="dialog-bottom-transition"
        >
            <v-card>
                <v-toolbar color="indigo" dark dense class="mb-2">
                    <v-toolbar-title>{{
                        editmode ? "Update Schedule" : "New Schedule"
                    }}</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-btn text @click="close">Close</v-btn>
                </v-toolbar>
                <v-card-text>
                    <v-row>
                        <v-col cols="12" md="6">
                            <v-text-field
                                label="Subject Code"
                                v-model="schedule_form.subject_code"
                                hint="Ex.CCIT 1"
                                dense
                                outlined
                            >
                            </v-text-field>
                            <v-text-field
                                label="Subject Description"
                                v-model="schedule_form.description"
                                hint="Ex. Data Mining"
                                dense
                                outlined
                            >
                            </v-text-field>
                            <v-select
                                label="Course"
                                v-model="schedule_form.course"
                                :items="[
                                    'Bachelor of Science  in Animal Science',
                                    'Bachelor of Science in Elementary Education',
                                    'Bachelor of Science in Secondary Education',
                                    'Bachelor of Science in Accounting Technology',
                                    'Bachelor of Science in Agriculture Business',
                                    'Bachelor of Science in Agriculture',
                                    'Bachelor of Science in Criminology',
                                    'Bachelor of Science in Hotel & Restaurant Management',
                                    'Bachelor of Science in Information Technology'
                                ]"
                                dense
                                outlined
                            >
                            </v-select>
                            <v-select
                                label="Year"
                                v-model="schedule_form.year"
                                :items="[
                                    'First Year',
                                    'Second Year',
                                    'Third Year',
                                    'Fourth Year'
                                ]"
                                dense
                                outlined
                            >
                            </v-select>
                            <v-select
                                label="Section"
                                v-model="schedule_form.section"
                                :items="[
                                    'Section A',
                                    'Section B',
                                    'Section C',
                                    'Section D',
                                    'Section E',
                                    'Section F'
                                ]"
                                dense
                                outlined
                            >
                            </v-select>
                            <v-select
                                label="Semester"
                                v-model="schedule_form.semester"
                                :items="['First Semester', 'Second Semester']"
                                dense
                                outlined
                            >
                            </v-select>
                            <v-select
                                label="School Year"
                                v-model="schedule_form.school_year"
                                :items="[
                                    new Date().getFullYear() +
                                        '-' +
                                        (Number(new Date().getFullYear()) + 1)
                                ]"
                                dense
                                outlined
                            >
                            </v-select>

                            <v-select
                                label="Room"
                                v-model="room"
                                :items="[
                                    'A1',
                                    'A2',
                                    'A3',
                                    'Laboratory 1',
                                    'Laboratory 2',
                                    'Laboratory 3',
                                    'Science Laboratory',
                                    'Library',
                                    'B4',
                                    'B5',
                                    'B6',
                                    'B7',
                                    'B8',
                                    'B9',
                                    'C1',
                                    'C2',
                                    'C3',
                                    'C4',
                                    'C5',
                                    'C6',
                                    'C7',
                                    'C8',
                                    'C9',
                                    'Stage',
                                    'Bar Room',
                                    'Hot Kitchen',
                                    'Balangaw Hall',
                                    'M I S Office',
                                    'C C J E Office',
                                    'HR Office',
                                    'Faculty Office',
                                    'Assessment Office',
                                    'Admin Office'
                                ]"
                                dense
                                outlined
                            >
                            </v-select>
                        </v-col>
                        <v-col cols="12" md="6">
                            <v-select
                                label="Day"
                                v-model="day"
                                :items="[
                                    'Monday',
                                    'Tuesday',
                                    'Wednesday',
                                    'Thursday',
                                    'Friday',
                                    'Saturday',
                                    'Sunday'
                                ]"
                                dense
                                outlined
                            >
                            </v-select>

                            <v-flex class="d-flex">
                                <v-row justify="space-around" align="center">
                                    <v-col
                                        style="width: 290px; flex: 0 1 auto;"
                                    >
                                        <h5>Start:</h5>
                                        <v-time-picker
                                            v-model="start"
                                            full-width="true"
                                        ></v-time-picker>
                                    </v-col>
                                    <v-col
                                        style="width: 290px; flex: 0 1 auto;"
                                    >
                                        <h5>End:</h5>
                                        <v-time-picker
                                            v-model="end"
                                            full-width="true"
                                        ></v-time-picker>
                                    </v-col>
                                </v-row>
                            </v-flex>
                            <v-spacer></v-spacer>
                            <v-btn
                                @click="addSlot()"
                                clas="mt-5"
                                rounded
                                small
                                color="success"
                                >Save</v-btn
                            >
                            <v-simple-table class="mt-2">
                                <template v-slot:default>
                                    <thead>
                                        <tr>
                                            <th class="text-left">Room</th>
                                            <th class="text-left">Day</th>
                                            <th class="text-left">Start</th>
                                            <th class="text-left">End</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="(item,
                                            i) in schedule_form.timeslot"
                                            :key="i"
                                        >
                                            <td>{{ item.room }}</td>
                                            <td>{{ item.day }}</td>
                                            <td>{{ item.start_time }}</td>
                                            <td>{{ item.end_time }}</td>
                                        </tr>
                                    </tbody>
                                </template>
                            </v-simple-table>
                        </v-col>
                    </v-row>
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn outlined rounded @click="close">Cancel</v-btn>
                    <v-btn
                        outlined
                        rounded
                        @click="editmode ? updateSchedule() : addSchedule()"
                        >{{ editmode ? "Update" : "Save" }}</v-btn
                    >
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-container>
</template>

<script>
export default {
    mounted() {
        console.log("Component mounted.");
    },

    created() {
        this.loadFaculty();
        Fire.$on("AfterCreate", () => {
            this.loadFaculty();
        });

        Fire.$on("AfterCreate", () => {
            this.loadSchedule();
        });
        // setInterval(() => this.loadFaculty(), 3000);
    },

    data() {
        return {
            search: null,
            editmode: false,
            faculties: [],
            schedules: {},
            dialog_faculty: false,
            dialog_schedule: false,
            show_password: false,
            sched_loaded: false,
            expanded: [],
            room: "",
            day: "",
            start: "",
            end: "",

            //   Data Table
            headers_faculty: [
                { text: "Photo", value: "photo" },
                { text: "Faculty Id", value: "faculty_id" },
                { text: "Name", value: "name" },
                { text: "Department", value: "department" },
                {
                    text: "Actions",
                    sortable: false,
                    value: "action",
                    align: "center"
                },
                { text: "", value: "data-table-expand" }
            ],

            // Data Tables Schedule
            headers_schedule: [
                { text: "Subject Code", value: "subject_code" },
                { text: "Description", value: "description" },
                { text: "Course", value: "course" },
                { text: "Year & Section", value: "class" },
                { text: "Timeslot", align: "center", value: "timeslot" },
                { text: "Semester", value: "semester" },
                { text: "S.Y", value: "school_year" },
                { text: "Actions", value: "action", align: "center" }
            ],

            // Faculty Form
            faculty_form: new Form({
                id: "",
                user_id: "",
                faculty_id: "",
                name: "",
                birthdate: "",
                email: "",
                address: "",
                degree: "",
                employment_status: "",
                department: "",
                position: "",
                username: "",
                password: "",
                photo: ""
            }),

            schedule_form: new Form({
                id: "",
                user_id: "",
                subject_code: "",
                description: "",
                course: "",
                year: "",
                section: "",

                timeslot: [],

                semester: "",
                school_year: ""
            })
        };
    },

    methods: {
        addSlot() {
            this.schedule_form.timeslot.push({
                room: this.room,
                day: this.day,
                start_time: this.start,
                end_time: this.end
            });
        },

        openSched(faculty) {
            this.schedule_form.user_id = faculty.user_id;
            this.dialog_schedule = true;
        },

        fileChange(file) {
            let reader = new FileReader();
            reader.onloadend = file => {
                this.faculty_form.photo = reader.result;
            };
            reader.readAsDataURL(file);
        },

        //Dialog Methods
        close() {
            this.dialog_faculty = false;
            this.dialog_schedule = false;
            this.faculty_form.reset();
        },

        updateFaculty() {
            this.$Progress.start();
            // console.log('Edit Mode');
            this.faculty_form
                .put("api/faculty/" + this.faculty_form.user_id)
                .then(() => {
                    Swal.fire(
                        "Successful!",
                        "Faculty Information has been update.",
                        "success"
                    );
                    this.faculty_form.reset();
                    Fire.$emit("AfterCreate");
                    this.dialog_faculty = false;
                })
                .catch(() => {
                    this.$Progress.fail();
                });
        },
        updateSchedule() {
            // console.log('Edit Mode');
            this.schedule_form
                .put("api/schedule/" + this.schedule_form.id)
                .then(() => {
                    Swal.fire(
                        "Successful!",
                        "Schedule has been update.",
                        "success"
                    );
                    this.schedule_form.reset();
                    Fire.$emit("AfterCreate");
                    this.dialog_schedule = false;
                })
                .catch(() => {
                    console.log("Error");
                });
        },

        editFaculty(faculty) {
            this.editmode = true;
            this.faculty_form.fill(faculty);
            this.dialog_faculty = true;
        },
        editSchedule(schedules) {
            this.editmode = true;
            this.schedule_form.fill(schedules);
            this.dialog_schedule = true;
        },

        addFaculty() {
            this.$Progress.start();
            axios.post("api/faculty", this.faculty_form).then(response => {
                this.close();
                this.faculties.unshift(response.data);
                Swal.fire("Registered!", "New faculty added!", "success");
                this.faculty_form.reset();
            });
            this.$Progress.finish();
        },

        addSchedule() {
            axios.post("api/schedule", this.schedule_form).then(response => {
                this.close();
                this.schedule_form.reset();
                Swal.fire("Successful!", "New Schedule added!", "success");
                Fire.$emit("AfterCreate");
            });
        },

        deleteFaculty(id) {
            // console.log(id);
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then(result => {
                if (result.value) {
                    axios
                        .delete("api/faculty/" + id)
                        .then(() => {
                            Swal.fire(
                                "Deleted!",
                                "Your file has been deleted.",
                                "success"
                            );
                            this.faculties.filter(
                                faculty => (faculty.user_id = id)
                            );
                        })
                        .catch(() => {
                            swal("Failed!", "Something went wrong.", "warning");
                        });
                }
            });
        },
        deleteSchedule(id) {
            // console.log(id);
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then(result => {
                if (result.value) {
                    axios
                        .delete("api/schedule/" + id)
                        .then(() => {
                            Swal.fire(
                                "Deleted!",
                                "Your file has been deleted.",
                                "success"
                            );
                            Fire.$emit("AfterCreate");
                            this.schedules.filter(
                                schedules => (schedule.id = id)
                            );
                        })
                        .catch(() => {
                            swal("Failed!", "Something went wrong.", "warning");
                        });
                }
            });
        },

        loadFaculty() {
            axios.get("api/faculty").then(({ data }) => {
                this.faculties = data.data;
            });
        },

        loadSchedule() {
            let id = this.expanded[0].user_id;
            axios
                .get("api/schedule/" + id)
                .then(data => {
                    this.schedules = data.data;
                })
                .then(() => {
                    this.sched_loaded = true;
                });
        }
    }
};
</script>
