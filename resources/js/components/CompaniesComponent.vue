<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Companies Component</div>

                    <div class="card-body">
                        <div class="input-group">
                            <button type="button" id="addCompanyBtn" class="btn btn-info m-2" onclick="$('#myTable').hide();$('#addCompanyForm').show();">Add Company</button>
                            <button type="button" id="viewCompaniesBtn" class="btn btn-info m-2" onclick="$('#addCompanyForm').hide();$('#myTable').show();">View Companies</button>
                        </div>
                        <div id="addCompanyForm" class="form-group" style="display: none">
                            <div v-if="validationErrors" class="alert alert-danger">
                                <ul>
                                    <li v-for="(error, key) in validationErrors" :key="key">{{ error[0] }}</li>
                                </ul>
                            </div>
                            <div v-if="successMessage" class="alert alert-success">
                                {{ successMessage }}
                            </div>
                            <div class="row mb-2">
                                <div class="col-2">
                                    <label for="name">Name</label>
                                </div>
                                <div class="col-10">
                                    <input type="text" name="name" id="name" v-model="name" class="form-control">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-2">
                                    <label for="email">Email</label>
                                </div>
                                <div class="col-10">
                                    <input type="text" name="email" id="email" v-model="email" class="form-control">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-2">
                                    <label for="logo">Logo</label>
                                </div>
                                <div class="col-10">
                                    <input type="file" name="logo" id="logo" ref="logo" accept="image/*" class="form-control" @change="handleFileChange">
                                    <img v-if="logoPreview" :src="logoPreview" height="50" width="50" class="mt-2">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-2">
                                    <label for="website">Website</label>
                                </div>
                                <div class="col-10">
                                    <input type="text" name="website" id="website" v-model="website" class="form-control">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <button v-if="!edit_company_id" type="button" class="btn btn-primary" @click="saveCompany()">Save</button>
                                <button v-else type="button" class="btn btn-primary" @click="updateCompany()">Update</button>
                            </div>
                        </div>
                        <table id="myTable" class="table table-bordered table-responsive display">
                            <thead>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Logo</th>
                            <th>Action</th>
                            </thead>
                            <tbody>
                            <tr v-for="(company, index) in companies" :key="company.id">
                                <td>{{ index + 1 }}</td>
                                <td><a :href="company.website">{{ company.name }}</a></td>
                                <td>{{ company.email }}</td>
                                <td>
                                    <img v-if="company.logo" :src="company.logo" height="50" width="50" alt="Company Logo">
                                    <span v-else>No Logo</span>
                                </td>
                                <td>
                                    <button class="btn btn-primary m-1" @click="editCompany(company)">Edit</button>
                                    <button class="btn btn-danger" @click="deleteCompany(company.id)">Delete</button>
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
export default {
    data() {
        return {
            companies: [],
            api: 'http://127.0.0.1:8000/api/companies',
            name: '',
            email: '',
            logo: null,
            logoPreview: null,
            website: '',
            edit_company_id: '',
            validationErrors: null,
            successMessage: null,
        }
    },
    methods: {
        getCompanies() {
            this.axios.get(this.api).then(res => {
                this.companies = res.data;
            }).catch(error => {
                console.error('Error fetching companies:', error);
            });
        },
        handleFileChange(event) {
            const file = event.target.files[0];
            if (file) {
                this.logo = file;
                this.logoPreview = URL.createObjectURL(file);
            } else {
                this.logo = null;
                this.logoPreview = null;
            }
        },
        saveCompany() {
            this.validationErrors = null;
            this.successMessage = null;

            let formData = new FormData();
            formData.append('name', this.name);
            formData.append('email', this.email);
            if (this.logo) {
                formData.append('logo', this.logo);
            }
            formData.append('website', this.website);

            this.axios.post(this.api, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then(res => {
                this.getCompanies();
                this.name = '';
                this.email = '';
                this.logo = null;
                this.logoPreview = null;
                this.website = '';
                this.$refs.logo.value = '';
                this.successMessage = 'Company added successfully!';
                $('#viewCompaniesBtn').trigger('click');
            }).catch(error => {
                if (error.response?.status === 422) {
                    this.validationErrors = error.response.data.errors;
                } else {
                    console.error('Error saving company:', error);
                    this.validationErrors = { general: ['An error occurred while saving the company.'] };
                }
            });
        },
        deleteCompany(id) {
            this.axios.delete(this.api + '/' + id).then(res => {
                this.getCompanies();
                this.successMessage = 'Company deleted successfully!';
            }).catch(error => {
                console.error('Error deleting company:', error);
                this.validationErrors = { general: ['An error occurred while deleting the company.'] };
            });
        },
        editCompany(company) {
            $('#myTable').hide();
            $('#addCompanyForm').show();
            this.name = company.name;
            this.email = company.email;
            this.logo = null;
            this.logoPreview = company.logo;
            this.website = company.website;
            this.edit_company_id = company.id;
            this.validationErrors = null;
            this.successMessage = null;
            this.$refs.logo.value = '';
        },
        updateCompany() {
            this.validationErrors = null;
            this.successMessage = null;

            let formData = new FormData();
            formData.append('name', this.name);
            formData.append('email', this.email);
            if (this.logo) {
                formData.append('logo', this.logo);
            }
            formData.append('website', this.website);
            formData.append('_method', 'PATCH');

            this.axios.post(this.api + '/' + this.edit_company_id, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then(res => {
                this.getCompanies();
                this.name = '';
                this.email = '';
                this.logo = null;
                this.logoPreview = null;
                this.website = '';
                this.edit_company_id = '';
                this.$refs.logo.value = '';
                this.successMessage = 'Company updated successfully!';
                $('#viewCompaniesBtn').trigger('click');
            }).catch(error => {
                if (error.response?.status === 422) {
                    this.validationErrors = error.response.data.errors;
                } else {
                    console.error('Error updating company:', error);
                    this.validationErrors = { general: ['An error occurred while updating the company.'] };
                }
            });
        }
    },
    mounted() {
        this.getCompanies();
    },
}
</script>
