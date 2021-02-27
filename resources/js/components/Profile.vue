<template>
    <v-container class="mt-3">
        <v-card width="800" class="mx-auto pa-4">
            <v-card-title class="h4">PROFILE </v-card-title>
            <v-card-content>
                <v-list-item class="blue lighten-5">
                    <v-list-item-avatar size="120">
                        <v-img
                            class="profile-user-img img-fluid img-circle"
                            :src="
                                '/img/profile_photos/' +
                                    $gate.auth().faculty_profile.photo
                            "
                            width="150"
                            alt="User profile picture"
                        />
                    </v-list-item-avatar>
                    <v-list-item-content>
                        <v-list-item-title
                            class="text-uppercase font-weight-bold"
                            >{{ profile.name }}</v-list-item-title
                        >
                        <v-list-item-subtitle>{{
                            profile.type
                        }}</v-list-item-subtitle>

                        <v-list-item-title class="mt-4"
                            ><span class="font-weight-bold mr-3"
                                >Faculty Id:</span
                            >{{
                                profile.faculty_profile.faculty_id
                            }}</v-list-item-title
                        >
                        <v-list-item-title
                            ><span class="font-weight-bold mr-3"
                                >Employment Status:</span
                            >{{
                                profile.faculty_profile.employment_status
                            }}</v-list-item-title
                        >
                        <v-list-item-title
                            ><span class="font-weight-bold mr-3">Email:</span
                            >{{ profile.email }}</v-list-item-title
                        >
                        <v-list-item-title
                            ><span class="font-weight-bold mr-3">Address:</span
                            >{{
                                profile.faculty_profile.address
                            }}</v-list-item-title
                        >
                        <v-list-item-title
                            ><span class="font-weight-bold mr-3"
                                >Date of Birth:</span
                            >{{
                                profile.faculty_profile.birthdate | shortDate
                            }}</v-list-item-title
                        >
                        <v-list-item-title
                            ><span class="font-weight-bold mr-3"
                                >Department:</span
                            >{{
                                profile.faculty_profile.department
                            }}</v-list-item-title
                        >
                    </v-list-item-content>
                </v-list-item>
                <p class="mx-auto text-red ma-4">NOTE: Please contact administrator for any updates.</p>
            </v-card-content>
        </v-card>
    </v-container>
</template>

<script>
export default {
    mounted() {
        console.log("Component mounted.");
    },

    created() {
        this.fetchProfile();
    },

    data() {
        return {
            profile: "",
            profile_form: new Form({
                id: "",
                faculty_profile_id: "",
                name: "",
                email: "",
                address: "",
                password: "",
                photo: "",
                position: ""
            })
        };
    },

    methods: {
        browseImage() {
            document.querySelector("#photo").click();
        },

        fileChange(e) {
            let file = e.target.files[0];
            let reader = new FileReader();
            reader.onloadend = file => {
                this.profile_form.photo = reader.result;
            };
            reader.readAsDataURL(file);
        },

        updateProfile(e) {
            console.log(e);
            this.$Progress.start();

            this.profile_form
                .put("api/faculty/" + this.profile_form.faculty_profile_id)
                .then(() => {
                    Swal.fire(
                        "Successful!",
                        "Profile Information has been update.",
                        "success"
                    );
                })
                .catch(() => {
                    this.$Progress.fail();
                });
        },

        fetchProfile() {
            axios.get("api/profile").then(response => {
                let user = response.data;
                this.profile = user;
                this.profile_form.faculty_profile_id = user.faculty_profile.id;
                this.profile_form.name = user.name;
                this.profile_form.email = user.email;
                this.profile_form.address = user.faculty_profile.address;
                this.profile_form.password = user.faculty_profile.password;
                this.profile_form.photo = user.faculty_profile.photos;
                this.profile_form.position = user.faculty_profile.position;
            });
        }
    }
};
</script>
