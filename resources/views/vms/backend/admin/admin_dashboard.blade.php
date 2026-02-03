@extends('layouts.admin')

@section('content')
            <!-- Header -->
            <div class="header-section">
                <div>
                    <h3 class="fw-800 mb-1 text-black letter-spacing-1"> Main Dashboard</h3>
                    <p class="sub-label mb-0">System Status & Monitoring</p>
                </div>
                <div class="header-profile-box glass-card">
                    <div class="avatar bg-primary">
                        <i class="fas fa-user-tie text-white small"></i>
                    </div>
                    <div>
                        <p class="small fw-800 mb-0 text-black">Admin Pankaj</p>
                        <span class="sub-label fs-9">Security Lead</span>
                    </div>
                </div>
            </div>

            <!-- Stats Row -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-xl">
                    <div class="glass-card summary-card">
                        <div>
                            <span class="sub-label d-block mb-1">Total Healthy</span>
                            <h2>20</h2>
                        </div>
                        <div class="summary-icon"><i class="fas fa-heartbeat"></i></div>
                    </div>
                </div>
                <div class="col-6 col-xl">
                    <div class="glass-card summary-card">
                        <div>
                            <span class="sub-label d-block mb-1">Need Attention</span>
                            <h2>10</h2>
                        </div>
                        <div class="summary-icon text-warning" style="background:rgba(255,193,7,0.1)"><i class="fas fa-exclamation-triangle"></i></div>
                    </div>
                </div>
                <div class="col-6 col-xl">
                    <div class="glass-card summary-card">
                        <div>
                            <span class="sub-label d-block mb-1">Sick</span>
                            <h2>05</h2>
                        </div>
                        <div class="summary-icon text-danger" style="background:rgba(220,53,69,0.1)"><i class="fas fa-procedures"></i></div>
                    </div>
                </div>
                <div class="col-6 col-xl">
                    <div class="glass-card summary-card">
                        <div>
                            <span class="sub-label d-block mb-1">Total Milk</span>
                            <h2>21</h2>
                        </div>
                        <div class="summary-icon" style="background:rgba(59,130,246,0.1)"><i class="fas fa-tint"></i></div>
                    </div>
                </div>
                <div class="col-12 col-xl">
                    <div class="glass-card summary-card justify-content-center cursor-pointer border-dashed" style="border-width: 2px;">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-plus"></i>
                            <span class="fw-bold text-uppercase fs-9">Add New Visitor</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Form -->
            <form action="{{ route('admin.animal.store') }}" method="POST" class="glass-card p-4 mb-4">
                @csrf
                <h6 class="fw-800 sub-label mb-4">Animal Information</h6>
                <div class="row g-3">
                    <!-- Animal ID -->
                    <div class="col-12 col-md-6">
                        <label for="animal_id" class="form-label">
                            <i class="fas fa-id-card"></i>
                            Animal ID
                        </label>
                        <input
                            type="text"
                            id="animal_id"
                            name="animal_id"
                            placeholder="e.g., COW-001"
                            class="input-dark"
                        >
                    </div>

                    <!-- Breed Type -->
                    <div class="col-12 col-md-6">
                        <label for="breed_type" class="form-label">
                            <i class="fas fa-paw"></i>
                            Breed Type
                        </label>
                        <select
                            id="breed_type"
                            name="breed_type"
                            class="input-dark"
                        >
                            <option value="">Select Breed</option>
                            <option value="Holstein">Holstein</option>
                            <option value="Jersey">Jersey</option>
                            <option value="Guernsey">Guernsey</option>
                            <option value="Ayrshire">Ayrshire</option>
                            <option value="Brown Swiss">Brown Swiss</option>
                            <option value="Milking Shorthorn">Milking Shorthorn</option>
                            <option value="Dutch Belted">Dutch Belted</option>
                            <option value="Red Poll">Red Poll</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <!-- Date -->
                    <div class="col-12 col-md-6">
                        <label for="date" class="form-label">
                            <i class="fas fa-calendar-alt"></i>
                            Date
                        </label>
                        <input
                            type="date"
                            id="date"
                            name="date"
                            class="input-dark"
                        >
                    </div>

                    <!-- Milk Collected -->
                    <div class="col-12 col-md-6">
                        <label for="milk_collected" class="form-label">
                            <i class="fas fa-tint"></i>
                            Milk Collected (L)
                        </label>
                        <input
                            type="number"
                            id="milk_collected"
                            name="milk_collected"
                            placeholder="e.g., 25"
                            step="0.1"
                            min="0"
                            class="input-dark"
                        >
                    </div>

                    <!-- Health Condition -->
                    <div class="col-12 col-md-6">
                        <label for="health_condition" class="form-label">
                            <i class="fas fa-heartbeat"></i>
                            Health Condition
                        </label>
                        <select
                            id="health_condition"
                            name="health_condition"
                            class="input-dark"
                        >
                            <option value="">Select Condition</option>
                            <option value="healthy">Healthy</option>
                            <option value="need_attention">Need Attention</option>
                            <option value="sick">Sick</option>
                        </select>
                    </div>

                    <!-- Weight -->
                    <div class="col-12 col-md-6">
                        <label for="weight" class="form-label">
                            <i class="fas fa-weight-hanging"></i>
                            Weight (kg)
                        </label>
                        <input
                            type="number"
                            id="weight"
                            name="weight"
                            placeholder="e.g., 450"
                            step="0.1"
                            min="0"
                            class="input-dark"
                        >
                    </div>

                    <!-- Notes -->
                    <div class="col-12">
                        <label for="notes" class="form-label">
                            <i class="fas fa-sticky-note"></i>
                            Notes (Optional)
                        </label>
                        <textarea
                            id="notes"
                            name="notes"
                            rows="3"
                            placeholder="Add any additional notes about the animal..."
                            class="input-dark"
                        ></textarea>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="row mt-4">
                    <div class="col-12">
                        <button type="submit" class="btn btn-gradient w-100">
                            <i class="fas fa-save me-2"></i>
                            Save Animal Information
                        </button>
                    </div>
                </div>
            </form>

            <!-- Milk Production Filter -->
            <div class="glass-card p-4 mb-4">
                <h6 class="fw-800 sub-label mb-4">Milk Production</h6>
                <div class="row g-3 align-items-end">
                    <!-- Month Selector -->
                    <div class="col-12 col-md-5">
                        <label for="month" class="form-label">
                            <i class="fas fa-calendar"></i>
                            Month
                        </label>
                        <select
                            id="month"
                            name="month"
                            class="input-dark"
                            onchange="updateMilkChart()"
                        >
                            <option value="1">January</option>
                            <option value="2">February</option>
                            <option value="3">March</option>
                            <option value="4">April</option>
                            <option value="5">May</option>
                            <option value="6">June</option>
                            <option value="7">July</option>
                            <option value="8">August</option>
                            <option value="9">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                        </select>
                    </div>

                    <!-- Year Selector -->
                    <div class="col-12 col-md-5">
                        <label for="year" class="form-label">
                            <i class="fas fa-calendar-alt"></i>
                            Year
                        </label>
                        <select
                            id="year"
                            name="year"
                            class="input-dark"
                            onchange="updateMilkChart()"
                        >
                            <option value="2024">2024</option>
                            <option value="2025">2025</option>
                            <option value="2026" selected>2026</option>
                            <option value="2027">2027</option>
                            <option value="2028">2028</option>
                        </select>
                    </div>

                    <!-- Filter Button -->
                    <div class="col-12 col-md-2">
                        <button type="button" onclick="updateMilkChart()" class="btn btn-gradient w-100 h-100">
                            <i class="fas fa-filter"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Milk Production Chart -->
            <div class="row g-4 mb-4">
                <div class="col-12">
                    <div class="glass-card p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h6 class="fw-800 sub-label mb-0">Daily Milk Production</h6>
                            <span class="sub-label fs-9" id="chart-period">January 2026</span>
                        </div>
                        <div class="bar-chart-wrapper">
                            <div class="d-flex">
                                <!-- Y-axis Labels -->
                                <div class="y-axis" id="y-axis">
                                    <!-- Y-axis labels will be generated by JavaScript -->
                                </div>
                                <!-- Chart Bars -->
                                <div class="bar-chart flex-grow-1" id="milk-chart">
                                    <!-- Bars will be generated by JavaScript -->
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between sub-label mt-3 fs-9" style="min-width: 500px;">
                            <span>1</span><span>5</span><span>10</span><span>15</span><span>20</span><span>25</span><span>30</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Visitor Statistics -->
            <div class="row g-4 mb-4">
                <div class="col-12 col-lg-8">
                    <div class="glass-card p-4">
                        <h6 class="fw-800 sub-label mb-4">Visitor Statistics</h6>
                        <div class="bar-chart-wrapper">
                            <div class="bar-chart">
                                <div class="bar-col" style="height: 70%;"></div>
                                <div class="bar-col" style="height: 90%;"></div>
                                <div class="bar-col" style="height: 30%;"></div>
                                <div class="bar-col" style="height: 60%;"></div>
                                <div class="bar-col" style="height: 80%;"></div>
                                <div class="bar-col" style="height: 95%;"></div>
                                <div class="bar-col" style="height: 50%;"></div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between sub-label mt-3 fs-9" style="min-width: 500px;">
                            <span>Mon</span><span>Tue</span><span>Wed</span><span>Thu</span><span>Fri</span><span>Sat</span><span>Sun</span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="glass-card p-4 h-100 text-center">
                        <h6 class="fw-800 sub-label text-start mb-4">Visit Categories</h6>
                        <div class="donut-container mx-auto mb-4">
                            <div class="donut-inner">
                                <span class="fw-800 fs-4 text-black">250</span>
                                <span class="sub-label" style="font-size: 8px; color: #000 !important;">Total</span>
                            </div>
                        </div>
                        <div class="row g-2 text-start small">
                            <div class="col-6 text-black"><i class="fas fa-circle me-1 text-primary"></i> Delivery</div>
                            <div class="col-6 text-black"><i class="fas fa-circle me-1 text-success"></i> Medical</div>
                            <div class="col-6 text-black"><i class="fas fa-circle me-1 text-warning"></i> Vendor</div>
                            <div class="col-6 text-black"><i class="fas fa-circle me-1 text-danger"></i> Clients</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Section (Log Container with Font Force) -->
            <div class="glass-card log-container p-4">
                <h6 class="fw-800 sub-label mb-4">Recent Visits Log</h6>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Visitor</th>
                                <th>Host</th>
                                <th>Dept</th>
                                <th>Duration</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="bg-opacity-10 rounded-circle" style="width: 25px; height: 25px;"></div>
                                        <span class="small fw-800">Adela Parkson</span>
                                    </div>
                                </td>
                                <td class="small">Vipul Gupta</td>
                                <td class="small">EPD</td>
                                <td class="small">1h 45m</td>
                                <td class="small">24/04/24</td>
                                <td><span class="status-badge">Completed</span></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="bg-opacity-10 rounded-circle" style="width: 25px; height: 25px;"></div>
                                        <span class="small fw-800">Jason Statham</span>
                                    </div>
                                </td>
                                <td class="small">Vipul Gupta</td>
                                <td class="small">Sales</td>
                                <td class="small">Active</td>
                                <td class="small">24/04/24</td>
                                <td><span class="status-badge text-warning border-orange">On-Site</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
@endsection

    <script>
        // Initialize milk chart on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateMilkChart();
        });

        // Update milk production chart based on month and year selection
        async function updateMilkChart() {
            const month = document.getElementById('month').value;
            const year = document.getElementById('year').value;
            const chartContainer = document.getElementById('milk-chart');
            const yAxisContainer = document.getElementById('y-axis');
            const periodLabel = document.getElementById('chart-period');
            
            // Update period label
            const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 
                              'July', 'August', 'September', 'October', 'November', 'December'];
            periodLabel.textContent = `${monthNames[month - 1]} ${year}`;

            // Show loading state
            chartContainer.innerHTML = '<div class="text-center w-100 py-4"><i class="fas fa-spinner fa-spin text-primary"></i> Loading...</div>';

            try {
                // Fetch data from animals table
                const response = await fetch(`{{ route('admin.animal.milk-production') }}?month=${month}&year=${year}`);
                const data = await response.json();

                // Clear loading state
                chartContainer.innerHTML = '';
                yAxisContainer.innerHTML = '';

                // Find maximum milk value for scaling
                const maxMilk = Math.max(...data.map(d => d.milk), 1);

                // Generate Y-axis labels (5 scale points)
                const yAxisLabels = [];
                for (let i = 5; i >= 0; i--) {
                    const value = (maxMilk / 5) * i;
                    yAxisLabels.push(value.toFixed(1));
                }

                // Create Y-axis labels
                yAxisLabels.forEach(label => {
                    const labelElement = document.createElement('div');
                    labelElement.className = 'y-axis-label';
                    labelElement.textContent = label + ' L';
                    yAxisContainer.appendChild(labelElement);
                });

                // Generate 30 bars (one for each day)
                data.forEach(dayData => {
                    const barHeight = (dayData.milk / maxMilk) * 100;
                    const bar = document.createElement('div');
                    bar.className = 'bar-col';
                    bar.style.height = `${barHeight}%`;
                    bar.title = `Day ${dayData.day}: ${dayData.milk.toFixed(1)}L`;
                    chartContainer.appendChild(bar);
                });

            } catch (error) {
                console.error('Error fetching milk production data:', error);
                chartContainer.innerHTML = '<div class="text-center w-100 py-4 text-danger">Error loading data</div>';
                yAxisContainer.innerHTML = '';
            }
        }
    </script>
