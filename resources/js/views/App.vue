<template>
    <v-app id="inspire">
        <!-- App Bar -->
        <v-app-bar
            :color="$gate.auth().type == 'Admin' ? 'black' : 'indigo'"
            dark
            app
            clipped-left
        >
            <v-app-bar-nav-icon
                @click.stop="drawer = !drawer"
            ></v-app-bar-nav-icon>
            <v-toolbar-title>
                <v-avatar>
                    <v-img
                        src="img/logo.png"
                        style="border-right:1px solid black"
                    ></v-img>
                </v-avatar>
                | Central Phillippine State University Faculty Monitoring System
            </v-toolbar-title>
            <v-spacer></v-spacer>
        </v-app-bar>

        <!-- Right Drawer -->
        <v-navigation-drawer
            :color="$gate.auth().type == 'Admin' ? 'grey darken-4' : 'indigo'"
            dark
            v-model="drawer"
            clipped
            app
        >
            <v-list-item>
                <v-list-item-avatar size="50">
                    <v-img v-if="$gate.auth().type == 'Admin'" src="img/avatar.png"></v-img>
                    <v-img v-else :src="'img/profile_photos/' + $gate.auth().faculty_profile.photo"></v-img>
                </v-list-item-avatar>
                <v-list-item-content>
                    <v-list-item-title>{{
                        $gate.auth().name
                    }}</v-list-item-title>
                    <v-list-item-subtitle>{{
                        $gate.auth().type
                    }}</v-list-item-subtitle>
                </v-list-item-content>
            </v-list-item>

            <v-divider></v-divider>

            <v-list dense>
                <v-list-item
                    v-for="link in links"
                    :key="link.path"
                    :to="link.path"
                    class="text-decoration-none"
                >
                    <v-list-item-action>
                        <v-icon>{{ link.icon }}</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>
                            {{ link.title }}
                        </v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item
                v-show="$gate.auth().type == 'Admin'"
                    to="/dashboard"
                    class="text-decoration-none"
                >
                    <v-list-item-action>
                        <v-icon>mdi-view-dashboard</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>
                            Dashboard
                        </v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item
                v-show="$gate.auth().type !== 'Admin'"
                    to="/profile"
                    class="text-decoration-none"
                >
                    <v-list-item-action>
                        <v-icon>mdi-account</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>
                            Profile
                        </v-list-item-title>
                    </v-list-item-content>
                </v-list-item>

                <!-- Admin Tabs -->
                <v-expansion-panels
                    v-if="$gate.auth().type == 'Admin'"
                    flat
                    hover
                >
                    <v-expansion-panel style="background-color:transparent;">
                        <v-expansion-panel-header
                            >Management</v-expansion-panel-header
                        >
                        <v-expansion-panel-content>
                            <v-list-item
                                v-for="link in admin"
                                :key="link.path"
                                :to="link.path"
                                class="text-decoration-none"
                            >
                                <v-list-item-action>
                                    <v-icon>{{ link.icon }}</v-icon>
                                </v-list-item-action>
                                <v-list-item-content>
                                    <v-list-item-title>{{
                                        link.title
                                    }}</v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                        </v-expansion-panel-content>
                    </v-expansion-panel>
                </v-expansion-panels>
            </v-list>
            <template v-slot:append>
                <div class="pa-2">
                    <v-btn block dark color="red" @click="logout">
                        <v-icon mr-2>mdi-power</v-icon> Log Out
                    </v-btn>
                </div>
            </template>
        </v-navigation-drawer>

        <!-- Content ROuter View -->
        <v-content>
            <!-- Router Views -->
            <router-view></router-view>
        </v-content>

        <!-- Footer -->
        <v-footer app>
            <div class="text-center">
                Copyright Reserve {{ new Date().getFullYear() }}
            </div>
        </v-footer>
    </v-app>
</template>

<script>
export default {
    data() {
        return {
            drawer: null,

            links: [
                {
                    title: "Home",
                    path: "/home",
                    icon: "mdi-home"
                }
            ],

            admin: [
                {
                    title: "Faculty",
                    path: "/faculty",
                    icon: "mdi-account-group"
                },
                {
                    title: "Students",
                    path: "/students",
                    icon: "mdi-clipboard-account"
                },
                {
                    title: "Schedule",
                    path: "/schedule",
                    icon: "mdi-clock"
                },
                {
                    title: "Rooms",
                    path: "/rooms",
                    icon: "mdi-office-building"
                }
            ]
        };
    },

    methods: {
        logout() {
            axios.post("logout").then(() => {
                document.location.href = "login";
            });
        }
    }
};
</script>
