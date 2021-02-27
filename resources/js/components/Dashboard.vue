<template>
  <v-container class="mt-2">
    <h3>Dashboard</h3>
    <v-divider></v-divider>
    <v-row>
      <!-- CCJE CARD -->
      <v-col cols="12" md="4">
        <v-card class="max-auto mr-1 mt-1" color="yellow" max-width="400" dark>
          <v-card-title>
            <v-avatar large left style="width:60px;margin-top:-45px;">
              <v-img src="img/crim.png"></v-img>
            </v-avatar>
            <span class="body-1 font-weight-light"
              >College of Business Management</span
            >
          </v-card-title>
          <v-card-actions>
            <v-list-item class="grow">
              <v-list-item-avatar color="grey darken-3">
                <v-img class="elevation-6" src="/img/avatar.png"></v-img>
              </v-list-item-avatar>

              <v-list-item-content>
                <v-list-item-title>CCJE HEAD</v-list-item-title>
              </v-list-item-content>

              <v-row align="center" justify="end">
                <v-icon class="mx-a">mdi-account</v-icon>
                <span class="subheading mr-2">256</span>
              </v-row>
            </v-list-item>
          </v-card-actions>
        </v-card>
      </v-col>
      <!-- CTE CARD -->
      <v-col cols="12" md="4">
        <v-card class="max-auto mr-1 mt-1" color="blue" max-width="400" dark>
          <v-card-title>
            <v-avatar large left style="width:60px;margin-top:-45px;">
              <v-img src="img/education.png"></v-img>
            </v-avatar>
            <span class="body-1 font-weight-light"
              >College of Teacher Education</span
            >
          </v-card-title>
          <v-card-actions>
            <v-list-item class="grow">
              <v-list-item-avatar color="grey darken-3">
                <v-img class="elevation-6" src="/img/avatar.png"></v-img>
              </v-list-item-avatar>

              <v-list-item-content>
                <v-list-item-title>CTE HEAD</v-list-item-title>
              </v-list-item-content>

              <v-row align="center" justify="end">
                <v-icon class="max-auto mr-1 mt-1">mdi-account</v-icon>
                <span class="subheading mr-2">256</span>
              </v-row>
            </v-list-item>
          </v-card-actions>
        </v-card>
      </v-col>

      <!-- CAF CARD -->
      <v-col cols="12" md="4">
        <v-card class="max-auto mr-1 mt-1" color="green" max-width="400" dark>
          <v-card-title>
            <v-avatar large left style="width:60px;margin-top:-45px;">
              <v-img src="img/agriculture.png"></v-img>
            </v-avatar>
            <span class="body-1 font-weight-light"
              >College of Argiculture & Forestry</span
            >
          </v-card-title>
          <v-card-actions>
            <v-list-item class="grow">
              <v-list-item-avatar color="grey darken-3">
                <v-img class="elevation-6" src="/img/avatar.png"></v-img>
              </v-list-item-avatar>

              <v-list-item-content>
                <v-list-item-title>CTE HEAD</v-list-item-title>
              </v-list-item-content>

              <v-row align="center" justify="end">
                <v-icon class="max-auto mr-1 mt-1">mdi-account</v-icon>
                <span class="subheading mr-2">256</span>
              </v-row>
            </v-list-item>
          </v-card-actions>
        </v-card>
      </v-col>
    </v-row>
    <!-- CCS CARD -->
    <v-row>
      <v-col cols="12" md="4">
        <v-card class="max-auto mr-1 mt-1" color="purple" dark>
          <v-card-title>
            <v-avatar large left style="width:60px;margin-top:-45px;">
              <v-img src="img/IT.png"></v-img>
            </v-avatar>
            <span class="body-1 font-weight-light"
              >College of Computer Studies</span
            >
          </v-card-title>
          <v-card-actions>
            <v-list-item class="grow">
              <v-list-item-avatar color="grey darken-3">
                <v-img class="elevation-6" src="/img/avatar.png"></v-img>
              </v-list-item-avatar>

              <v-list-item-content>
                <v-list-item-title>CCS HEAD</v-list-item-title>
              </v-list-item-content>
              <v-row align="center" justify="end">
                <v-icon class="max-auto mr-1 mt-1">mdi-account</v-icon>
                <span class="subheading mr-2">256</span>
              </v-row>
            </v-list-item>
          </v-card-actions>
        </v-card>
      </v-col>
      <!-- CBM CARD -->
      <v-col cols="12" md="4">
        <v-card class="max-auto mr-1 mt-1" color="#800000" dark>
          <v-card-title color="indigo">
            <v-avatar large left style="width:50px;margin-top:-45px;">
              <v-img src="img/logo.png"></v-img>
            </v-avatar>
            <span class="body-1 font-weight-light"
              >College of Criminal Justice Education</span
            >
          </v-card-title>
          <v-card-actions>
            <v-list-item class="grow">
              <v-list-item-avatar color="grey darken-3">
                <v-img class="elevation-6" src="/img/avatar.png"></v-img>
              </v-list-item-avatar>

              <v-list-item-content>
                <v-list-item-title>CTE HEAD</v-list-item-title>
              </v-list-item-content>

              <v-row align="center" justify="end">
                <v-icon class="max-auto mr-1 mt-1">mdi-account</v-icon>
                <span class="subheading mr-2">256</span>
              </v-row>
            </v-list-item>
          </v-card-actions>
        </v-card>
      </v-col>
    </v-row>

    <v-divider></v-divider>
    <h3>Analytics</h3>
    <v-divider></v-divider>
    <v-row>
         <v-col cols="12" md="6" sm="3">
          <v-card hover>
            <v-card-title style="background-color:blue;color:white;">

            </v-card-title>
              <template >
            <div>
              <apexchart
                width="500"
                type="bar"
                :data="barData.data"
                :options="barData.options"
                :series="barData.series"
                color="green"
              ></apexchart>
            </div>
                 </template>
          </v-card>

      </v-col>
      <v-col cols="12" md="6" sm="3">

          <v-card hover>
            <v-card-title style="background-color:blue;color:white;">

            </v-card-title>
              <template>
            <div id="chart">
              <apexchart

                type="pie"
                width="440"
                :options="chartOptions"
                :series="series"
              ></apexchart>
            </div>
            </template>
          </v-card>

      </v-col>

    </v-row>
  </v-container>
</template>

<script>
export default {
  mounted() {},
  //Pie Graph

  data: function() {
    return {

      series: [256, 256, 256, 256, 256],
      chartOptions: {
        chart: {
          width: 200,
          type: "pie"
        },
        labels: ["CTE", "CAF", "CBM", "CCJE", "CCS"],
        responsive: [
          {
            breakpoint: 480,
            options: {
              chart: {
                width: 200
              },
              legend: {
                position: "bottom"
              }
            }
          }
        ]
      },
      barData:{
          options: {
        chart: {
            width:380,
          id: 'vuechart-example'
        },
        xaxis: {
          categories: ['PhD','Masters','Bachelors','Permanent','Temporary','Part-Time']
        }
      },
      series: [{
        name: 'series-1',
        data: [30, 40, 45, 50, 49, 60]
      }]
      }


    };

  }


};
</script>
