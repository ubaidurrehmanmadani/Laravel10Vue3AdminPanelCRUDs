<template>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Employees Component</div>

                    <div class="card-body">
                        <div class="input-group">
                            <button type="button" id="addEmployeeBtn" class="btn btn-info m-2" onclick="$('#employeesTable').hide();$('#addEmployeeForm').show();">Add Employee</button>
                            <button type="button" id="viewEmployeesBtn" class="btn btn-info m-2" onclick="$('#addEmployeeForm').hide();$('#employeesTable').show();">View Employees</button>
                        </div>
                        <div id="addEmployeeForm" class="form-group" style="display: none">
                            <div class="row mb-2">
                                <div class="col-2">
                                    <label for="name">First Name</label>
                                </div>
                                <div class="col-10">
                                    <input type="text" name="emp_firstname" id="emp_firstname" v-model="emp_firstname" class="form-control">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-2">
                                    <label for="name">Last Name</label>
                                </div>
                                <div class="col-10">
                                    <input type="text" name="emp_lastname" id="emp_lastname" v-model="emp_lastname" class="form-control">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-2">
                                    <label for="name">Company</label>
                                </div>
                                <div class="col-10">
                                    <select name="emp_company" id="emp_company" v-model="emp_company" class="form-control">
                                        <option v-for="(company, index) in companies" :key="index" :value="company.id">{{ company.name }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-2">
                                    <label for="name">Email</label>
                                </div>
                                <div class="col-10">
                                    <input type="text" name="emp_email" id="emp_email" v-model="emp_email" class="form-control">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-2">
                                    <label for="name">Phone</label>
                                </div>
                                <div class="col-10">
                                    <input type="text" name="emp_phone" id="emp_phone" v-model="emp_phone" class="form-control">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <button v-if="!edit_Employee_id" type="button" class="btn btn-primary" @click="saveEmployee()">Save</button>
                                <button v-else type="button" class="btn btn-primary" @click="updateEmployee()">Update</button>
                            </div>
                        </div>
                        <table id="employeesTable" class="table table-bordered table-responsive display">
                            <thead>
                            <tr>
                                <th>Index</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Company</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(Employee, index) in Employees.data" :key="index">
                                <td>{{ index+1 }}</td>
                                <td>{{ Employee.firstname }}</td>
                                <td>{{ Employee.lastname }}</td>
                                <td>{{ Employee.company_name }}</td>
                                <td>{{ Employee.email }}</td>
                                <td>{{ Employee.phone}}</td>
                                <td>
                                    <button class="btn btn-primary m-1" @click="editEmployee(Employee)">Edit</button>
                                    <button class="btn btn-danger" @click="deleteEmployee(Employee.id)">Delete</button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
// import VuePaginate from 'vue-paginate'

export default {
    // components: {
    //     VuePaginate
    // },
    data(){
        return {
            Employees: [],
            api: 'http://127.0.0.1:8000/api/employees',
            emp_firstname: '',
            emp_lastname:'',
            emp_company:'',
            emp_email: '',
            emp_phone: '',
            edit_Employee_id: '',
            companies: []
        }
    },
    methods:{
        getCompanies() {
            this.axios.get('http://127.0.0.1:8000/api/get_all_companies').then(res => {
                this.companies = res.data;
            });

        },
        getEmployees() {
            this.axios.get('http://127.0.0.1:8000/api/get_all_employees').then(res => {
                this.Employees = { data: res.data }; // Ensure correct structure
            }).catch(err => {
                console.error('Error fetching employees:', err);
                alert('Failed to load employees');
            });
        },
        saveEmployee(){
            let data = {
                'firstname': this.emp_firstname,
                'lastname': this.emp_lastname,
                'company_id': this.emp_company,
                'email': this.emp_email,
                'phone': this.emp_phone,
            };
            this.axios.post(this.api, data).then(res => {
                this.Employees.data.push(res.data);
                this.emp_firstname = this.emp_lastname = this.emp_company = this.emp_email = this.emp_phone = '';
                $('#viewEmployeesBtn').trigger('click');
            });
        },
        deleteEmployee(id){
            console.log(id)
            this.axios.delete(this.api+ '/' + id).then(res => {
                this.Employees.data.pop(res);
            });
        },
        editEmployee(Employee){
            $('#employeesTable').hide();
            $('#addEmployeeForm').show();
            this.emp_firstname = Employee.firstname;
            this.emp_lastname = Employee.lastname;
            this.emp_company = Employee.company_id;
            this.emp_email = Employee.email;
            this.emp_phone = Employee.phone;
            this.edit_Employee_id = Employee.id;
        },
        updateEmployee(){
            let data = {
                'firstname': this.emp_firstname,
                'lastname': this.emp_lastname,
                'company_id': this.emp_company,
                'email': this.emp_email,
                'phone': this.emp_phone,
            };
            this.axios.patch(this.api + '/' + this.edit_Employee_id, data).then(res => {
                this.Employees.data.pop(res.data);
                this.Employees.data.push(res.data);
                $('#viewEmployeesBtn').trigger('click');
            });
        }
    },
    mounted() {
        this.getCompanies();
        this.getEmployees();
        // this.axios.get(this.api).then(res => {
        //     // this.Employees = res.data;
        //     console.log(res.data)
        //     // $('#employeesTable').DataTable( {
        //     //     data: res.data,
        //     //     columns: [
        //     //         { data: 'id' },
        //     //         { data: 'name' },
        //     //         { data: 'email' },
        //     //         { data: 'logo' }
        //     //     ]
        //     // } );
        //
        // });
    },
}
</script>
