@extends('layouts.app')

@section('content')
        <!-- container -->
        <div class="custom-container">
          <!-- row -->
          <div class="row mb-6 g-6">
            <div class="col-xl-6 col-md-12 col-12">
              <div class="row row-cols-md-2 row-cols-1 g-6">
                <div class="col">
                  <!-- card -->
                  <div class="card card-lg">
                    <!-- card body -->
                    <div class="card-body">
                      <div class="d-flex flex-column gap-4">
                        <!-- heading -->
                        <div class="d-flex justify-content-between align-items-center">
                          <span class="fw-semibold">Bounce Rate [Avg]</span>
                          <span>
                            <svg
                              xmlns="http://www.w3.org/2000/svg"
                              class="icon icon-tabler icon-tabler-activity text-primary"
                              width="28"
                              height="28"
                              viewBox="0 0 24 24"
                              stroke-width="1.5"
                              stroke="currentColor"
                              fill="none"
                              stroke-linecap="round"
                              stroke-linejoin="round"
                            >
                              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                              <path d="M3 12h4l3 8l4 -16l3 8h4" />
                            </svg>
                          </span>
                        </div>
                        <div>
                          <div class="fs-2 fw-bold">47.74%</div>

                          <div>
                            <span class="text-danger">
                              <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="icon icon-tabler icon-tabler-arrow-down"
                                width="14"
                                height="14"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                fill="none"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                              >
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 5l0 14" />
                                <path d="M18 13l-6 6" />
                                <path d="M6 13l6 6" />
                              </svg>
                              -26.50%
                            </span>
                            <small class="text-secondary">vs 66.88(prev.)</small>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col">
                  <!-- card -->
                  <div class="card card-lg">
                    <!-- card body -->
                    <div class="card-body">
                      <div class="d-flex flex-column gap-4">
                        <!-- heading -->
                        <div class="d-flex justify-content-between align-items-center">
                          <span class="fw-semibold">New Sessions</span>
                          <span>
                            <svg
                              xmlns="http://www.w3.org/2000/svg"
                              width="24"
                              height="24"
                              viewBox="0 0 24 24"
                              fill="none"
                              stroke="currentColor"
                              stroke-width="1.5"
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              class="icon icon-tabler icons-tabler-outline icon-tabler-chart-line text-info"
                            >
                              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                              <path d="M4 19l16 0" />
                              <path d="M4 15l4 -6l4 2l4 -5l4 4" />
                            </svg>
                          </span>
                        </div>
                        <div>
                          <div class="fs-2 fw-bold">76.40%</div>

                          <div>
                            <span class="text-success">
                              <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="icon icon-tabler icon-tabler-arrow-up"
                                width="14"
                                height="14"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                fill="none"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                              >
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 5l0 14" />
                                <path d="M18 11l-6 -6" />
                                <path d="M6 11l6 -6" />
                              </svg>
                              -2.50%
                            </span>
                            <small class="text-secondary">vs 74.60(prev.)</small>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col">
                  <!-- card -->
                  <div class="card card-lg">
                    <!-- card body -->
                    <div class="card-body">
                      <div class="d-flex flex-column gap-4">
                        <!-- heading -->
                        <div class="d-flex justify-content-between align-items-center">
                          <span class="fw-semibold">Page Views</span>
                          <span>
                            <svg
                              xmlns="http://www.w3.org/2000/svg"
                              width="24"
                              height="24"
                              viewBox="0 0 24 24"
                              fill="none"
                              stroke="currentColor"
                              stroke-width="1.5"
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              class="icon icon-tabler icons-tabler-outline icon-tabler-file-report text-danger"
                            >
                              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                              <path d="M17 17m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                              <path d="M17 13v4h4" />
                              <path d="M12 3v4a1 1 0 0 0 1 1h4" />
                              <path d="M11.5 21h-6.5a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v2m0 3v4" />
                            </svg>
                          </span>
                        </div>
                        <div>
                          <div class="fs-2 fw-bold">2.15</div>

                          <div>
                            <span class="text-danger">
                              <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="icon icon-tabler icon-tabler-arrow-down"
                                width="14"
                                height="14"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                fill="none"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                              >
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 5l0 14" />
                                <path d="M18 13l-6 6" />
                                <path d="M6 13l6 6" />
                              </svg>
                              -1.83%
                            </span>
                            <small class="text-secondary">vs 2.19 (prev.)</small>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col">
                  <!-- card -->
                  <div class="card card-lg">
                    <!-- card body -->
                    <div class="card-body">
                      <div class="d-flex flex-column gap-4">
                        <!-- heading -->
                        <div class="d-flex justify-content-between align-items-center">
                          <span class="fw-semibold">Time on Site [Avg]</span>
                          <span>
                            <svg
                              xmlns="http://www.w3.org/2000/svg"
                              class="icon icon-tabler icon-tabler-clock text-warning"
                              width="24"
                              height="24"
                              viewBox="0 0 24 24"
                              stroke-width="1.5"
                              stroke="currentColor"
                              fill="none"
                              stroke-linecap="round"
                              stroke-linejoin="round"
                            >
                              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                              <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
                              <path d="M12 7v5l3 3" />
                            </svg>
                          </span>
                        </div>
                        <div>
                          <div class="fs-2 fw-bold">2m:15s</div>

                          <div>
                            <span class="text-success">
                              <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="icon icon-tabler icon-tabler-arrow-up"
                                width="14"
                                height="14"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                fill="none"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                              >
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 5l0 14" />
                                <path d="M18 11l-6 -6" />
                                <path d="M6 11l6 -6" />
                              </svg>
                              21.50%
                            </span>
                            <small class="text-secondary">vs 2.19 (prev.)</small>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-6 col-md-12 col-12">
              <!-- card -->
              <div class="card card-lg">
                <!-- card body -->
                <div class="card-body">
                  <!-- header -->
                  <h5 class="mb-1">Device Breakdown</h5>
                  <span class="text-secondary">By User count</span>

                  <div class="d-flex flex-column gap-6 mt-9">
                    <div>
                      <div class="mb-3 d-flex justify-content-between align-items-center">
                        <div class="d-flex gap-2 align-items-center">
                          <span>
                            <svg
                              xmlns="http://www.w3.org/2000/svg"
                              width="20"
                              height="20"
                              viewBox="0 0 24 24"
                              fill="none"
                              stroke="currentColor"
                              stroke-width="1.5"
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              class="icon icon-tabler icons-tabler-outline icon-tabler-device-imac text-secondary"
                            >
                              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                              <path d="M3 4a1 1 0 0 1 1 -1h16a1 1 0 0 1 1 1v12a1 1 0 0 1 -1 1h-16a1 1 0 0 1 -1 -1v-12z" />
                              <path d="M3 13h18" />
                              <path d="M8 21h8" />
                              <path d="M10 17l-.5 4" />
                              <path d="M14 17l.5 4" />
                            </svg>
                          </span>
                          <span> Desktop</span>
                        </div>
                        <div class="d-flex gap-2 align-items-center">
                          <div>201,434</div>
                          <div>51.5%</div>
                        </div>
                      </div>

                      <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="65" aria-valuemin="65" aria-valuemax="100" style="height: 6px">
                        <div class="progress-bar bg-warning" style="width: 65%"></div>
                      </div>
                    </div>
                    <div>
                      <div class="mb-3 d-flex justify-content-between align-items-center">
                        <div class="d-flex gap-2 align-items-center">
                          <span>
                            <svg
                              xmlns="http://www.w3.org/2000/svg"
                              width="20"
                              height="20"
                              viewBox="0 0 24 24"
                              fill="none"
                              stroke="currentColor"
                              stroke-width="1.5"
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              class="icon icon-tabler icons-tabler-outline icon-tabler-device-mobile text-secondary"
                            >
                              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                              <path d="M6 5a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-8a2 2 0 0 1 -2 -2v-14z" />
                              <path d="M11 4h2" />
                              <path d="M12 17v.01" />
                            </svg>
                          </span>
                          <span> Mobile</span>
                        </div>
                        <div class="d-flex gap-2 align-items-center">
                          <div>134,693</div>
                          <div>34.4%</div>
                        </div>
                      </div>

                      <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="34" aria-valuemin="34" aria-valuemax="100" style="height: 6px">
                        <div class="progress-bar bg-success" style="width: 34%"></div>
                      </div>
                    </div>
                    <div>
                      <div class="mb-3 d-flex justify-content-between align-items-center">
                        <div class="d-flex gap-2 align-items-center">
                          <span>
                            <svg
                              xmlns="http://www.w3.org/2000/svg"
                              width="20"
                              height="20"
                              viewBox="0 0 24 24"
                              fill="none"
                              stroke="currentColor"
                              stroke-width="1.5"
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              class="icon icon-tabler icons-tabler-outline icon-tabler-device-laptop text-secondary"
                            >
                              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                              <path d="M3 19l18 0" />
                              <path d="M5 6m0 1a1 1 0 0 1 1 -1h12a1 1 0 0 1 1 1v8a1 1 0 0 1 -1 1h-12a1 1 0 0 1 -1 -1z" />
                            </svg>
                          </span>
                          <span> Laptop</span>
                        </div>
                        <div class="d-flex gap-2 align-items-center">
                          <div>81,525</div>
                          <div>20.8%</div>
                        </div>
                      </div>

                      <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="43" aria-valuemin="43" aria-valuemax="100" style="height: 6px">
                        <div class="progress-bar bg-info" style="width: 43%"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- row -->
          <div class="row mb-6 g-6">
            <div class="col-12 col-lg-4">
              <!-- card -->
              <div class="card card-lg">
                <!-- card body -->
                <div class="card-body">
                  <div class="mb-6">
                    <!-- heading -->
                    <h5 class="mb-1">Users by Country</h5>
                    <span class="text-secondary">Small content for the card.</span>
                  </div>
                  <div class="d-flex justify-content-center align-items-center">
                    <div id="userByCountry"></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-12 col-lg-8">
              <!-- card -->
              <div class="card card-lg">
                <!-- card body -->
                <div class="card-body">
                  <div class="mb-6">
                    <!-- heading -->
                    <h5 class="mb-1">Traffic summary</h5>
                    <span class="text-secondary">Small content for the card.</span>
                  </div>
                  <div class="d-flex justify-content-center align-items-center">
                    <div id="trafficSummary" style="width: 100%"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- row -->
          <div class="row mb-6 g-6">
            <div class="col-lg-6 col-xl-7 col-12">
              <!-- card -->
              <div class="card card-lg">
                <!-- card header -->
                <div class="card-header border-bottom-0">
                  <h5 class="mb-0">Most Visited Pages</h5>
                </div>
                <!-- table -->
                <div class="table-responsive">
                  <table class="table mb-0 text-nowrap table-centered">
                    <thead>
                      <tr>
                        <th>Landing page</th>
                        <th>Visitors</th>
                        <th>Unique</th>
                        <th>Bounce rate</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>
                          /
                          <a href="#!" class="link-primary">
                            <svg
                              xmlns="http://www.w3.org/2000/svg"
                              class="icon icon-tabler icon-tabler-external-link"
                              width="16"
                              height="16"
                              viewBox="0 0 24 24"
                              stroke-width="1.5"
                              stroke="currentColor"
                              fill="none"
                              stroke-linecap="round"
                              stroke-linejoin="round"
                            >
                              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                              <path d="M12 6h-6a2 2 0 0 0 -2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-6" />
                              <path d="M11 13l9 -9" />
                              <path d="M15 4h5v5" />
                            </svg>
                          </a>
                        </td>
                        <td>5,056</td>
                        <td>3,654</td>
                        <td>82.54%</td>
                      </tr>

                      <tr>
                        <td>
                          /landings/courses.html
                          <a href="#!" class="link-primary">
                            <svg
                              xmlns="http://www.w3.org/2000/svg"
                              class="icon icon-tabler icon-tabler-external-link"
                              width="16"
                              height="16"
                              viewBox="0 0 24 24"
                              stroke-width="1.5"
                              stroke="currentColor"
                              fill="none"
                              stroke-linecap="round"
                              stroke-linejoin="round"
                            >
                              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                              <path d="M12 6h-6a2 2 0 0 0 -2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-6" />
                              <path d="M11 13l9 -9" />
                              <path d="M15 4h5v5" />
                            </svg>
                          </a>
                        </td>
                        <td>2,385</td>
                        <td>2,385</td>
                        <td>76.29%</td>
                      </tr>
                      <tr>
                        <td>
                          /landings/lead.html
                          <a href="#!" class="link-primary">
                            <svg
                              xmlns="http://www.w3.org/2000/svg"
                              class="icon icon-tabler icon-tabler-external-link"
                              width="16"
                              height="16"
                              viewBox="0 0 24 24"
                              stroke-width="1.5"
                              stroke="currentColor"
                              fill="none"
                              stroke-linecap="round"
                              stroke-linejoin="round"
                            >
                              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                              <path d="M12 6h-6a2 2 0 0 0 -2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-6" />
                              <path d="M11 13l9 -9" />
                              <path d="M15 4h5v5" />
                            </svg>
                          </a>
                        </td>
                        <td>634</td>
                        <td>634</td>
                        <td>72.65%</td>
                      </tr>
                      <tr>
                        <td>
                          /dashboard.html
                          <a href="#!" class="link-primary">
                            <svg
                              xmlns="http://www.w3.org/2000/svg"
                              class="icon icon-tabler icon-tabler-external-link"
                              width="16"
                              height="16"
                              viewBox="0 0 24 24"
                              stroke-width="1.5"
                              stroke="currentColor"
                              fill="none"
                              stroke-linecap="round"
                              stroke-linejoin="round"
                            >
                              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                              <path d="M12 6h-6a2 2 0 0 0 -2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-6" />
                              <path d="M11 13l9 -9" />
                              <path d="M15 4h5v5" />
                            </svg>
                          </a>
                        </td>
                        <td>650</td>
                        <td>650</td>
                        <td>44.89%</td>
                      </tr>
                      <tr>
                        <td>
                          /checkout.html
                          <a href="#!" class="link-primary">
                            <svg
                              xmlns="http://www.w3.org/2000/svg"
                              class="icon icon-tabler icon-tabler-external-link"
                              width="16"
                              height="16"
                              viewBox="0 0 24 24"
                              stroke-width="1.5"
                              stroke="currentColor"
                              fill="none"
                              stroke-linecap="round"
                              stroke-linejoin="round"
                            >
                              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                              <path d="M12 6h-6a2 2 0 0 0 -2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-6" />
                              <path d="M11 13l9 -9" />
                              <path d="M15 4h5v5" />
                            </svg>
                          </a>
                        </td>
                        <td>243</td>
                        <td>243</td>
                        <td>41.12%</td>
                      </tr>
                      <tr>
                        <td>
                          /blog.html
                          <a href="#!" class="link-primary">
                            <svg
                              xmlns="http://www.w3.org/2000/svg"
                              class="icon icon-tabler icon-tabler-external-link"
                              width="16"
                              height="16"
                              viewBox="0 0 24 24"
                              stroke-width="1.5"
                              stroke="currentColor"
                              fill="none"
                              stroke-linecap="round"
                              stroke-linejoin="round"
                            >
                              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                              <path d="M12 6h-6a2 2 0 0 0 -2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-6" />
                              <path d="M11 13l9 -9" />
                              <path d="M15 4h5v5" />
                            </svg>
                          </a>
                        </td>
                        <td>209</td>
                        <td>209</td>
                        <td>32.56%</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div class="card-footer px-4 border-top border-dashed">
                  <a href="#"> View More </a>
                </div>
              </div>
            </div>
            <div class="col-lg-6 col-xl-5 col-12">
              <!-- card -->
              <div class="card card-lg">
                <!-- card header -->
                <div class="card-header d-flex justify-content-between align-items-center">
                  <h5 class="mb-0">Website traffic source</h5>
                  <a href="#!" class="text-secondary">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="20"
                      height="20"
                      viewBox="0 0 24 24"
                      fill="none"
                      stroke="currentColor"
                      stroke-width="1.5"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      class="icon icon-tabler icons-tabler-outline icon-tabler-external-link"
                    >
                      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                      <path d="M12 6h-6a2 2 0 0 0 -2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-6" />
                      <path d="M11 13l9 -9" />
                      <path d="M15 4h5v5" />
                    </svg>
                  </a>
                </div>
                <!-- table -->
                <div class="table-responsive">
                  <table class="table mb-0 text-nowrap table-centered">
                    <tbody>
                      <tr>
                        <td>
                          <div class="d-flex align-items-center gap-2">
                            <div class="icon-shape icon-md bg-gray-200 rounded-2">
                              <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="20"
                                height="20"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.5"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-rocket text-gray-600"
                              >
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M4 13a8 8 0 0 1 7 7a6 6 0 0 0 3 -5a9 9 0 0 0 6 -8a3 3 0 0 0 -3 -3a9 9 0 0 0 -8 6a6 6 0 0 0 -5 3" />
                                <path d="M7 14a6 6 0 0 0 -3 6a6 6 0 0 0 6 -3" />
                                <path d="M15 9m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                              </svg>
                            </div>
                            <div>Direct Source</div>
                          </div>
                        </td>
                        <td class="text-end">
                          <div class="d-flex gap-2 align-items-center justify-content-end">
                            <div>6,860</div>
                            <div class="badge text-success-emphasis bg-success-subtle d-inline-flex align-items-center">
                              <span>
                                <svg
                                  xmlns="http://www.w3.org/2000/svg"
                                  width="16"
                                  height="16"
                                  viewBox="0 0 24 24"
                                  fill="none"
                                  stroke="currentColor"
                                  stroke-width="1.5"
                                  stroke-linecap="round"
                                  stroke-linejoin="round"
                                  class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-up"
                                >
                                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                  <path d="M12 5l0 14" />
                                  <path d="M18 11l-6 -6" />
                                  <path d="M6 11l6 -6" />
                                </svg>
                              </span>
                              <span>12.3%</span>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <div class="d-flex align-items-center gap-2 lh-1">
                            <div class="icon-shape icon-md bg-gray-200 rounded-2">
                              <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="20"
                                height="20"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.5"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-world text-gray-600"
                              >
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
                                <path d="M3.6 9h16.8" />
                                <path d="M3.6 15h16.8" />
                                <path d="M11.5 3a17 17 0 0 0 0 18" />
                                <path d="M12.5 3a17 17 0 0 1 0 18" />
                              </svg>
                            </div>
                            <div>Google</div>
                          </div>
                        </td>
                        <td class="text-end">
                          <div class="d-flex gap-2 align-items-center justify-content-end lh-1">
                            <div>4,768</div>
                            <div class="badge text-success-emphasis bg-success-subtle d-inline-flex align-items-center">
                              <span>
                                <svg
                                  xmlns="http://www.w3.org/2000/svg"
                                  width="16"
                                  height="16"
                                  viewBox="0 0 24 24"
                                  fill="none"
                                  stroke="currentColor"
                                  stroke-width="1.5"
                                  stroke-linecap="round"
                                  stroke-linejoin="round"
                                  class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-up"
                                >
                                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                  <path d="M12 5l0 14" />
                                  <path d="M18 11l-6 -6" />
                                  <path d="M6 11l6 -6" />
                                </svg>
                              </span>
                              <span>8.2%</span>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <div class="d-flex align-items-center gap-2 lh-1">
                            <div class="icon-shape icon-md bg-gray-200 rounded-2">
                              <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="20"
                                height="20"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.5"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-affiliate text-gray-600"
                              >
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M5.931 6.936l1.275 4.249m5.607 5.609l4.251 1.275" />
                                <path d="M11.683 12.317l5.759 -5.759" />
                                <path d="M5.5 5.5m-1.5 0a1.5 1.5 0 1 0 3 0a1.5 1.5 0 1 0 -3 0" />
                                <path d="M18.5 5.5m-1.5 0a1.5 1.5 0 1 0 3 0a1.5 1.5 0 1 0 -3 0" />
                                <path d="M18.5 18.5m-1.5 0a1.5 1.5 0 1 0 3 0a1.5 1.5 0 1 0 -3 0" />
                                <path d="M8.5 15.5m-4.5 0a4.5 4.5 0 1 0 9 0a4.5 4.5 0 1 0 -9 0" />
                              </svg>
                            </div>
                            <div>Referrals</div>
                          </div>
                        </td>
                        <td class="text-end">
                          <div class="d-flex gap-2 align-items-center justify-content-end lh-1">
                            <div>425</div>
                            <div class="badge text-danger-emphasis bg-danger-subtle d-inline-flex align-items-center">
                              <span>
                                <svg
                                  xmlns="http://www.w3.org/2000/svg"
                                  width="16"
                                  height="16"
                                  viewBox="0 0 24 24"
                                  fill="none"
                                  stroke="currentColor"
                                  stroke-width="1.5"
                                  stroke-linecap="round"
                                  stroke-linejoin="round"
                                  class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-down"
                                >
                                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                  <path d="M12 5l0 14" />
                                  <path d="M18 13l-6 6" />
                                  <path d="M6 13l6 6" />
                                </svg>
                              </span>
                              <span>2.3%</span>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <div class="d-flex align-items-center gap-2 lh-1">
                            <div class="icon-shape icon-md bg-gray-200 rounded-2">
                              <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="20"
                                height="20"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.5"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-external-link text-gray-600"
                              >
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 6h-6a2 2 0 0 0 -2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-6" />
                                <path d="M11 13l9 -9" />
                                <path d="M15 4h5v5" />
                              </svg>
                            </div>
                            <div>Social Media</div>
                          </div>
                        </td>
                        <td class="text-end">
                          <div class="d-flex gap-2 align-items-center justify-content-end lh-1">
                            <div>245</div>
                            <div class="badge text-success-emphasis bg-success-subtle d-inline-flex align-items-center">
                              <span>
                                <svg
                                  xmlns="http://www.w3.org/2000/svg"
                                  width="16"
                                  height="16"
                                  viewBox="0 0 24 24"
                                  fill="none"
                                  stroke="currentColor"
                                  stroke-width="1.5"
                                  stroke-linecap="round"
                                  stroke-linejoin="round"
                                  class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-up"
                                >
                                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                  <path d="M12 5l0 14" />
                                  <path d="M18 11l-6 -6" />
                                  <path d="M6 11l6 -6" />
                                </svg>
                              </span>
                              <span>2.5%</span>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <div class="d-flex align-items-center gap-2 lh-1">
                            <div class="icon-shape icon-md bg-gray-200 rounded-2">
                              <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="20"
                                height="20"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.5"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-speakerphone text-gray-600"
                              >
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M18 8a3 3 0 0 1 0 6" />
                                <path d="M10 8v11a1 1 0 0 1 -1 1h-1a1 1 0 0 1 -1 -1v-5" />
                                <path d="M12 8h0l4.524 -3.77a.9 .9 0 0 1 1.476 .692v12.156a.9 .9 0 0 1 -1.476 .692l-4.524 -3.77h-8a1 1 0 0 1 -1 -1v-4a1 1 0 0 1 1 -1h8" />
                              </svg>
                            </div>
                            <div>Ads</div>
                          </div>
                        </td>
                        <td class="text-end">
                          <div class="d-flex gap-2 align-items-center justify-content-end lh-1">
                            <div>125</div>
                            <div class="badge text-success-emphasis bg-success-subtle d-inline-flex align-items-center">
                              <span>
                                <svg
                                  xmlns="http://www.w3.org/2000/svg"
                                  width="16"
                                  height="16"
                                  viewBox="0 0 24 24"
                                  fill="none"
                                  stroke="currentColor"
                                  stroke-width="1.5"
                                  stroke-linecap="round"
                                  stroke-linejoin="round"
                                  class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-up"
                                >
                                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                  <path d="M12 5l0 14" />
                                  <path d="M18 11l-6 -6" />
                                  <path d="M6 11l6 -6" />
                                </svg>
                              </span>
                              <span>1.5%</span>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <div class="d-flex align-items-center gap-2 lh-1">
                            <div class="icon-shape icon-md bg-gray-200 rounded-2">
                              <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="20"
                                height="20"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.5"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-link text-gray-600"
                              >
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M9 15l6 -6" />
                                <path d="M11 6l.463 -.536a5 5 0 0 1 7.071 7.072l-.534 .464" />
                                <path d="M13 18l-.397 .534a5.068 5.068 0 0 1 -7.127 0a4.972 4.972 0 0 1 0 -7.071l.524 -.463" />
                              </svg>
                            </div>
                            <div>Others</div>
                          </div>
                        </td>
                        <td class="text-end">
                          <div class="d-flex gap-2 align-items-center justify-content-end lh-1">
                            <div>245</div>
                            <div class="badge text-danger-emphasis bg-danger-subtle d-inline-flex align-items-center">
                              <span>
                                <svg
                                  xmlns="http://www.w3.org/2000/svg"
                                  width="16"
                                  height="16"
                                  viewBox="0 0 24 24"
                                  fill="none"
                                  stroke="currentColor"
                                  stroke-width="1.5"
                                  stroke-linecap="round"
                                  stroke-linejoin="round"
                                  class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-down"
                                >
                                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                  <path d="M12 5l0 14" />
                                  <path d="M18 13l-6 6" />
                                  <path d="M6 13l6 6" />
                                </svg>
                              </span>
                              <span>2.3%</span>
                            </div>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <!-- row -->
          <div class="row row-cols-xxl-3 row-cols-xl-2 row-cols-md-1 g-6">
            <div class="col">
              <!-- card -->
              <div class="card card-lg">
                <!-- card -body-->
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center mb-6">
                    <!-- heading -->
                    <h5 class="mb-0">Demographics</h5>
                    <div class="dropdown dropstart">
                      <a href="#!" class="btn btn-ghost btn-icon btn-sm rounded-circle" data-bs-toggle="dropdown" aria-expanded="false">
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          class="icon icon-tabler icon-tabler-dots-vertical"
                          width="20"
                          height="20"
                          viewBox="0 0 24 24"
                          stroke-width="1.5"
                          stroke="currentColor"
                          fill="none"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                        >
                          <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                          <path d="M12 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                          <path d="M12 19m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                          <path d="M12 5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                        </svg>
                      </a>
                      <ul class="dropdown-menu">
                        <li>
                          <a class="dropdown-item d-flex align-items-center" href="#!">Action</a>
                        </li>
                        <li>
                          <a class="dropdown-item d-flex align-items-center" href="#!">Another action</a>
                        </li>
                        <li>
                          <a class="dropdown-item d-flex align-items-center" href="#!">Something else here</a>
                        </li>
                      </ul>
                    </div>
                  </div>
                  <div id="chartGraphics"></div>
                </div>
              </div>
            </div>
            <div class="col">
              <!-- card -->
              <div class="card card-lg">
                <!-- card body-->
                <div class="card-body">
                  <div class="mb-6">
                    <h5 class="mb-0">Social traffic</h5>
                  </div>
                  <div>
                    <div class="card mb-3">
                      <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                          <div class="d-flex align-items-center gap-3">
                            <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <g clip-path="">
                                <path d="M0 0H32V32H0V0Z" fill="black" />
                                <path
                                  fill-rule="evenodd"
                                  clip-rule="evenodd"
                                  d="M12.0021 6.125H5.1875L13.2672 16.7973L5.70437 25.75H9.19862L14.9187 18.9788L19.9979 25.6877H26.8125L18.498 14.7052L18.5128 14.7242L25.6716 6.24963H22.1774L16.861 12.543L12.0021 6.125ZM8.94906 7.99406H11.0706L23.0509 23.8186H20.9294L8.94906 7.99406Z"
                                  fill="white"
                                />
                              </g>
                              <defs>
                                <clipPath>
                                  <rect width="32" height="32" fill="white" />
                                </clipPath>
                              </defs>
                            </svg>
                            <div class="lh-1">
                              <div class="fs-5 fw-bold mb-1">240</div>
                              <div class="text-secondary">15 retweets</div>
                            </div>
                          </div>
                          <div class="d-flex align-items-center text-success lh-1 gap-2">
                            <div>13%</div>
                            <div>
                              <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="20"
                                height="20"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.5"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-trending-up"
                              >
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M3 17l6 -6l4 4l8 -8" />
                                <path d="M14 7l7 0l0 7" />
                              </svg>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="card mb-3">
                      <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                          <div class="d-flex align-items-center gap-3">
                            <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <g clip-path="">
                                <path
                                  d="M10.6682 16C10.6682 13.0546 13.0552 10.6662 16.0006 10.6662C18.946 10.6662 21.3344 13.0546 21.3344 16C21.3344 18.9454 18.946 21.3338 16.0006 21.3338C13.0552 21.3338 10.6682 18.9454 10.6682 16ZM7.78483 16C7.78483 20.5376 11.463 24.2158 16.0006 24.2158C20.5382 24.2158 24.2164 20.5376 24.2164 16C24.2164 11.4624 20.5382 7.78419 16.0006 7.78419C11.463 7.78419 7.78483 11.4624 7.78483 16ZM22.6217 7.45843C22.6215 7.83817 22.734 8.20943 22.9448 8.52526C23.1557 8.84108 23.4555 9.08729 23.8062 9.23275C24.157 9.37821 24.543 9.41639 24.9155 9.34246C25.288 9.26852 25.6302 9.0858 25.8988 8.81739C26.1674 8.54898 26.3504 8.20694 26.4247 7.83453C26.4989 7.46211 26.461 7.07605 26.3158 6.72516C26.1707 6.37426 25.9247 6.0743 25.609 5.8632C25.2934 5.65211 24.9222 5.53935 24.5425 5.5392H24.5417C24.0327 5.53944 23.5446 5.74169 23.1846 6.10155C22.8246 6.4614 22.6221 6.94942 22.6217 7.45843ZM9.53664 29.0236C7.9767 28.9526 7.12883 28.6927 6.56538 28.4732C5.81837 28.1824 5.28538 27.836 4.72499 27.2764C4.16461 26.7168 3.81773 26.1843 3.52819 25.4373C3.30854 24.8741 3.0487 24.026 2.97779 22.466C2.90022 20.7795 2.88474 20.2729 2.88474 16.0001C2.88474 11.7274 2.9015 11.2221 2.97779 9.53421C3.04883 7.97427 3.31059 7.12781 3.52819 6.56294C3.81901 5.81594 4.16538 5.28294 4.72499 4.72256C5.28461 4.16218 5.81709 3.8153 6.56538 3.52576C7.12858 3.30611 7.9767 3.04627 9.53664 2.97536C11.2232 2.89779 11.7298 2.8823 16.0006 2.8823C20.2715 2.8823 20.7786 2.89907 22.4666 2.97536C24.0265 3.0464 24.873 3.30816 25.4378 3.52576C26.1848 3.8153 26.7178 4.16294 27.2782 4.72256C27.8386 5.28218 28.1842 5.81594 28.475 6.56294C28.6947 7.12614 28.9545 7.97427 29.0254 9.53421C29.103 11.2221 29.1185 11.7274 29.1185 16.0001C29.1185 20.2729 29.103 20.7781 29.0254 22.466C28.9544 24.026 28.6932 24.8739 28.475 25.4373C28.1842 26.1843 27.8378 26.7173 27.2782 27.2764C26.7186 27.8355 26.1848 28.1824 25.4378 28.4732C24.8746 28.6929 24.0265 28.9527 22.4666 29.0236C20.78 29.1012 20.2734 29.1167 16.0006 29.1167C11.7279 29.1167 11.2227 29.1012 9.53664 29.0236ZM9.40416 0.096896C7.70086 0.174464 6.53696 0.444544 5.52051 0.840064C4.46784 1.24851 3.5767 1.79648 2.68621 2.68557C1.79571 3.57466 1.24915 4.4672 0.840705 5.51987C0.445185 6.53696 0.175105 7.70022 0.0975369 9.40352C0.0186889 11.1095 0.000640869 11.6549 0.000640869 16C0.000640869 20.3451 0.0186889 20.8905 0.0975369 22.5965C0.175105 24.2999 0.445185 25.463 0.840705 26.4801C1.24915 27.5322 1.79584 28.4257 2.68621 29.3144C3.57658 30.2031 4.46784 30.7503 5.52051 31.1599C6.53888 31.5555 7.70086 31.8255 9.40416 31.9031C11.111 31.9807 11.6556 32 16.0006 32C20.3457 32 20.8911 31.982 22.5971 31.9031C24.3005 31.8255 25.4637 31.5555 26.4808 31.1599C27.5328 30.7503 28.4246 30.2035 29.3151 29.3144C30.2056 28.4253 30.751 27.5322 31.1606 26.4801C31.5561 25.463 31.8275 24.2998 31.9037 22.5965C31.9813 20.8892 31.9994 20.3451 31.9994 16C31.9994 11.6549 31.9813 11.1095 31.9037 9.40352C31.8262 7.7001 31.5561 6.53632 31.1606 5.51987C30.751 4.46784 30.2042 3.57606 29.3151 2.68557C28.426 1.79507 27.5328 1.24851 26.482 0.840064C25.4637 0.444544 24.3004 0.173184 22.5984 0.096896C20.8924 0.019328 20.347 0 16.0019 0C11.6568 0 11.111 0.018048 9.40416 0.096896Z"
                                  fill="url(#paint0_radial_1078_6209)"
                                />
                                <path
                                  d="M10.6682 16C10.6682 13.0546 13.0552 10.6662 16.0006 10.6662C18.946 10.6662 21.3344 13.0546 21.3344 16C21.3344 18.9454 18.946 21.3338 16.0006 21.3338C13.0552 21.3338 10.6682 18.9454 10.6682 16ZM7.78483 16C7.78483 20.5376 11.463 24.2158 16.0006 24.2158C20.5382 24.2158 24.2164 20.5376 24.2164 16C24.2164 11.4624 20.5382 7.78419 16.0006 7.78419C11.463 7.78419 7.78483 11.4624 7.78483 16ZM22.6217 7.45843C22.6215 7.83817 22.734 8.20943 22.9448 8.52526C23.1557 8.84108 23.4555 9.08729 23.8062 9.23275C24.157 9.37821 24.543 9.41639 24.9155 9.34246C25.288 9.26852 25.6302 9.0858 25.8988 8.81739C26.1674 8.54898 26.3504 8.20694 26.4247 7.83453C26.4989 7.46211 26.461 7.07605 26.3158 6.72516C26.1707 6.37426 25.9247 6.0743 25.609 5.8632C25.2934 5.65211 24.9222 5.53935 24.5425 5.5392H24.5417C24.0327 5.53944 23.5446 5.74169 23.1846 6.10155C22.8246 6.4614 22.6221 6.94942 22.6217 7.45843ZM9.53664 29.0236C7.9767 28.9526 7.12883 28.6927 6.56538 28.4732C5.81837 28.1824 5.28538 27.836 4.72499 27.2764C4.16461 26.7168 3.81773 26.1843 3.52819 25.4373C3.30854 24.8741 3.0487 24.026 2.97779 22.466C2.90022 20.7795 2.88474 20.2729 2.88474 16.0001C2.88474 11.7274 2.9015 11.2221 2.97779 9.53421C3.04883 7.97427 3.31059 7.12781 3.52819 6.56294C3.81901 5.81594 4.16538 5.28294 4.72499 4.72256C5.28461 4.16218 5.81709 3.8153 6.56538 3.52576C7.12858 3.30611 7.9767 3.04627 9.53664 2.97536C11.2232 2.89779 11.7298 2.8823 16.0006 2.8823C20.2715 2.8823 20.7786 2.89907 22.4666 2.97536C24.0265 3.0464 24.873 3.30816 25.4378 3.52576C26.1848 3.8153 26.7178 4.16294 27.2782 4.72256C27.8386 5.28218 28.1842 5.81594 28.475 6.56294C28.6947 7.12614 28.9545 7.97427 29.0254 9.53421C29.103 11.2221 29.1185 11.7274 29.1185 16.0001C29.1185 20.2729 29.103 20.7781 29.0254 22.466C28.9544 24.026 28.6932 24.8739 28.475 25.4373C28.1842 26.1843 27.8378 26.7173 27.2782 27.2764C26.7186 27.8355 26.1848 28.1824 25.4378 28.4732C24.8746 28.6929 24.0265 28.9527 22.4666 29.0236C20.78 29.1012 20.2734 29.1167 16.0006 29.1167C11.7279 29.1167 11.2227 29.1012 9.53664 29.0236ZM9.40416 0.096896C7.70086 0.174464 6.53696 0.444544 5.52051 0.840064C4.46784 1.24851 3.5767 1.79648 2.68621 2.68557C1.79571 3.57466 1.24915 4.4672 0.840705 5.51987C0.445185 6.53696 0.175105 7.70022 0.0975369 9.40352C0.0186889 11.1095 0.000640869 11.6549 0.000640869 16C0.000640869 20.3451 0.0186889 20.8905 0.0975369 22.5965C0.175105 24.2999 0.445185 25.463 0.840705 26.4801C1.24915 27.5322 1.79584 28.4257 2.68621 29.3144C3.57658 30.2031 4.46784 30.7503 5.52051 31.1599C6.53888 31.5555 7.70086 31.8255 9.40416 31.9031C11.111 31.9807 11.6556 32 16.0006 32C20.3457 32 20.8911 31.982 22.5971 31.9031C24.3005 31.8255 25.4637 31.5555 26.4808 31.1599C27.5328 30.7503 28.4246 30.2035 29.3151 29.3144C30.2056 28.4253 30.751 27.5322 31.1606 26.4801C31.5561 25.463 31.8275 24.2998 31.9037 22.5965C31.9813 20.8892 31.9994 20.3451 31.9994 16C31.9994 11.6549 31.9813 11.1095 31.9037 9.40352C31.8262 7.7001 31.5561 6.53632 31.1606 5.51987C30.751 4.46784 30.2042 3.57606 29.3151 2.68557C28.426 1.79507 27.5328 1.24851 26.482 0.840064C25.4637 0.444544 24.3004 0.173184 22.5984 0.096896C20.8924 0.019328 20.347 0 16.0019 0C11.6568 0 11.111 0.018048 9.40416 0.096896Z"
                                  fill="url(#paint1_radial_1078_6209)"
                                />
                              </g>
                              <defs>
                                <radialGradient id="paint0_radial_1078_6209" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(4.25203 32.1512) scale(41.7733 41.7733)">
                                  <stop offset="0.09" stop-color="#FA8F21" />
                                  <stop offset="0.78" stop-color="#D82D7E" />
                                </radialGradient>
                                <radialGradient id="paint1_radial_1078_6209" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(19.4072 33.5848) scale(32.9231)">
                                  <stop offset="0.64" stop-color="#8C3AAA" stop-opacity="0" />
                                  <stop offset="1" stop-color="#8C3AAA" />
                                </radialGradient>
                                <clipPath>
                                  <rect width="32" height="32" fill="white" />
                                </clipPath>
                              </defs>
                            </svg>
                            <div class="lh-1">
                              <div class="fs-5 fw-bold mb-1">887</div>
                              <div class="text-secondary">25 followers</div>
                            </div>
                          </div>
                          <div class="d-flex align-items-center text-danger lh-1 gap-2">
                            <div>- 2.5%</div>
                            <div>
                              <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="20"
                                height="20"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.5"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-trending-down"
                              >
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M3 7l6 6l4 -4l8 8" />
                                <path d="M21 10l0 7l-7 0" />
                              </svg>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="card mb-3">
                      <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                          <div class="d-flex align-items-center gap-3">
                            <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <g clip-path="">
                                <path d="M21.333 15.8853C18.407 14.3217 15.6054 12.8842 12.7416 11.3833V20.3618C15.7552 18.7236 18.9316 17.2233 21.3454 15.8853H21.333Z" fill="white" />
                                <path d="M21.333 15.8853C18.407 14.3217 12.7416 11.3833 12.7416 11.3833L20.2951 16.4602C20.2951 16.4602 18.9191 17.2233 21.333 15.8853Z" fill="#E8E0E0" />
                                <path
                                  d="M13.2922 27.39C7.21477 27.2781 5.13903 27.1775 3.86315 26.9146C2.99993 26.74 2.2498 26.3522 1.69978 25.7897C1.27468 25.364 0.936619 24.7145 0.674339 23.8134C0.449358 23.0627 0.361733 22.4381 0.23681 20.9124C0.0455759 17.469 -1.23233e-05 14.6537 0.23681 11.5082C0.432188 9.77107 0.526917 7.70894 1.8247 6.50588C2.43748 5.94284 3.15031 5.5805 3.95077 5.43071C5.20119 5.1933 10.5285 5.00562 16.0435 5.00562C21.5461 5.00562 26.8852 5.1933 28.1368 5.43071C29.1368 5.61839 30.0746 6.18084 30.6252 6.90611C31.8093 8.76872 31.8301 11.0848 31.9503 12.8965C32 13.7597 32 18.6614 31.9503 19.5246C31.7632 22.3878 31.6128 23.4008 31.1877 24.4511C30.9248 25.1142 30.7004 25.4641 30.312 25.8519C29.7029 26.4487 28.9264 26.8456 28.0859 26.9898C22.8267 27.3853 18.3608 27.4712 13.2922 27.39ZM21.3459 15.8852C18.42 14.3216 15.6184 12.8717 12.7546 11.3708V20.3499C15.7682 18.7117 18.9452 17.2108 21.3584 15.8728L21.3459 15.8852Z"
                                  fill="#CD201F"
                                />
                              </g>
                              <defs>
                                <clipPath>
                                  <rect width="32" height="22.4768" fill="white" transform="translate(0 5)" />
                                </clipPath>
                              </defs>
                            </svg>

                            <div class="lh-1">
                              <div class="fs-5 fw-bold mb-1">887</div>
                              <div class="text-secondary">98 Views today</div>
                            </div>
                          </div>
                          <div class="d-flex align-items-center text-warning lh-1 gap-2">
                            <div>0</div>
                            <div>
                              <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="20"
                                height="20"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.5"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-line-dashed"
                              >
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M5 12h2" />
                                <path d="M17 12h2" />
                                <path d="M11 12h2" />
                              </svg>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="card">
                      <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                          <div class="d-flex align-items-center gap-3">
                            <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <g clip-path="">
                                <path d="M2.40732 3.98352H28.9155V29.3515H2.40732V3.98352Z" fill="white" />
                                <path
                                  d="M0.0320129 2.32009C0.0320129 1.05676 1.08885 0.0319824 2.39194 0.0319824H29.6081C30.9112 0.0319824 31.968 1.05676 31.968 2.32009V29.6812C31.968 30.9445 30.9112 31.968 29.6081 31.968H2.39194C1.08885 31.968 0.0320129 30.9445 0.0320129 29.6812V2.32009Z"
                                  fill="#0A66C2"
                                />
                                <path
                                  d="M9.7372 26.7582V12.3806H4.95834V26.7582H9.7372ZM7.34905 10.4183C9.01511 10.4183 10.0527 9.31397 10.0527 7.93392C10.0219 6.5231 9.01511 5.44958 7.38112 5.44958C5.74584 5.44958 4.67746 6.5231 4.67746 7.93392C4.67746 9.31397 5.71378 10.4183 7.31827 10.4183H7.34905ZM12.3819 26.7582H17.1607V18.7293C17.1607 18.2997 17.1915 17.87 17.3185 17.5635C17.6635 16.7041 18.4497 15.8153 19.7707 15.8153C21.4997 15.8153 22.191 17.1338 22.191 19.0666V26.7569H26.9698V18.5138C26.9698 14.098 24.6124 12.0433 21.4689 12.0433C18.8909 12.0433 17.7597 13.4836 17.1299 14.4648H17.162V12.3806H12.3819C12.4447 13.7299 12.3819 26.7582 12.3819 26.7582Z"
                                  fill="white"
                                />
                              </g>
                              <defs>
                                <clipPath>
                                  <rect width="32" height="31.936" fill="white" transform="translate(0 0.0319824)" />
                                </clipPath>
                              </defs>
                            </svg>

                            <div class="lh-1">
                              <div class="fs-5 fw-bold mb-1">305</div>
                              <div class="text-secondary">25 Connections</div>
                            </div>
                          </div>
                          <div class="d-flex align-items-center text-success lh-1 gap-2">
                            <div>10 %</div>
                            <div>
                              <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="20"
                                height="20"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.5"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-trending-up"
                              >
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M3 17l6 -6l4 4l8 -8" />
                                <path d="M14 7l7 0l0 7" />
                              </svg>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col">
              <!-- card -->
              <div class="card card-lg">
                <!-- card body-->
                <div class="card-body">
                  <h5 class="mb-0">Browsers</h5>
                </div>
                <!-- table -->
                <table class="table mb-0">
                  <tbody>
                    <tr>
                      <td class="border-top-0">
                        <img src="/assets/images/svg/browser-svg/chrome.svg" alt="Image" class="me-2" />
                        <span>Google Chrome</span>
                      </td>
                      <td class="text-end border-top-0">21.54%</td>
                    </tr>
                    <tr>
                      <td>
                        <img src="/assets/images/svg/browser-svg/firefox.svg" alt="Image" class="me-2" />
                        <span>Mozilla Firefox</span>
                      </td>
                      <td class="text-end">14.43%</td>
                    </tr>
                    <tr>
                      <td>
                        <img src="/assets/images/svg/browser-svg/brave.svg" alt="Image" class="me-2" />
                        <span>Brave</span>
                      </td>
                      <td class="text-end">14.43%</td>
                    </tr>
                    <tr>
                      <td>
                        <img src="/assets/images/svg/browser-svg/safari.svg" alt="Image" class="me-2" />
                        <span>Apple Safari</span>
                      </td>
                      <td class="text-end">8.54%</td>
                    </tr>
                    <tr>
                      <td>
                        <img src="/assets/images/svg/browser-svg/ie.svg" alt="Image" class="me-2" />
                        <span>Internet Export</span>
                      </td>
                      <td class="text-end">6.21%</td>
                    </tr>
                    <tr>
                      <td>
                        <img src="/assets/images/svg/browser-svg/opera.svg" alt="Image" class="me-2" />
                        <span>Opera Mini</span>
                      </td>
                      <td class="text-end">8%</td>
                    </tr>
                    <tr>
                      <td>
                        <img src="/assets/images/svg/browser-svg/microsoft-edge.svg" alt="Image" class="me-2" />
                        <span>Microsoft edge</span>
                      </td>
                      <td class="text-end">12.9%</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
@endsection
