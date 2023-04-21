<script setup>
import { Link, useForm } from "@inertiajs/vue3";
import {
    Alert,
    Button,
    Modal,
    // Input,
    Table,
    // TableHead,
    // TableBody,
    // TableHeadCell,
    // TableRow,
    // TableCell,
} from "flowbite-vue";
import { ref, nextTick, onMounted } from "vue";
import InputLabel from "./InputLabel.vue";
import Input from "./Input.vue";

defineProps({
    workExperiences: Object,
});

const countryOptions = [
    { id: 1, name: "Australia" },
    { id: 2, name: "Fiji" },
    { id: 3, name: "New Zealand" },
    { id: 4, name: "Samoa" },
    { id: 5, name: "United States of America" },
];
const isShowModal = ref(false);
const organisation = ref(null);

const form = useForm({
    organisation: null,
    position: null,
    responsibilities: null,
    from_date: null,
    to_date: null,
});



function closeModal() {
    isShowModal.value = false;
}
function showModal() {
    isShowModal.value = true;

    nextTick(() => {
        organisation.value.focus();
    });
}
function addWorkExperience() {
    form.post("/member-work-experiences", {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
    closeModal();
}
</script>
<template>
    <div>
        <h5>Work Experience</h5>

        <Link href="#">
            <Button
                class="p-3 mt-3"
                color="alternative"
                @click.prevent="showModal"
                >Add Work Experience</Button
            >
        </Link>

        <div class="relative mt-3 overflow-x-auto shadow-md sm:rounded-lg">
            <table
                class="w-full text-sm text-left text-gray-500 dark:text-gray-400"
            >
                <thead
                    class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
                >
                    <tr>
                        <th scope="col" class="px-6 py-3">Organisation</th>
                        <th scope="col" class="px-6 py-3">Position</th>
                        <th scope="col" class="px-6 py-3">Responsibilities</th>
                        <th scope="col" class="px-6 py-3">Date</th>
                        <th scope="col" class="px-6 py-3">
                            <span class="sr-only">Edit</span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700"
                    >
                        <th
                            scope="row"
                            class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                        >
                            Apple MacBook Pro 17"
                        </th>
                        <td class="px-6 py-4">Sliver</td>
                        <td class="px-6 py-4">Laptop</td>
                        <td class="px-6 py-4">$2999</td>
                        <td class="px-6 py-4 text-right">
                            <a
                                href="#"
                                class="font-medium text-red-600 dark:text-red-500 hover:underline"
                                >Delete</a
                            >
                        </td>
                    </tr>
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700"
                    >
                        <th
                            scope="row"
                            class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                        >
                            Microsoft Surface Pro
                        </th>
                        <td class="px-6 py-4">White</td>
                        <td class="px-6 py-4">Laptop PC</td>
                        <td class="px-6 py-4">$1999</td>
                        <td class="px-6 py-4 text-right">
                            <a
                                href="#"
                                class="font-medium text-red-600 dark:text-red-500 hover:underline"
                                >Delete</a
                            >
                        </td>
                    </tr>
                    <tr class="bg-white dark:bg-gray-800">
                        <th
                            scope="row"
                            class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                        >
                            Magic Mouse 2
                        </th>
                        <td class="px-6 py-4">Black</td>
                        <td class="px-6 py-4">Accessories</td>
                        <td class="px-6 py-4">$99</td>
                        <td class="px-6 py-4 text-right">
                            <a
                                href="#"
                                class="font-medium text-red-600 dark:text-red-500 hover:underline"
                                >Delete</a
                            >
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <form @submit.prevent="addWorkExperience">
        <Modal v-if="isShowModal" @close="closeModal">
            <template #header>
                <div class="flex items-center text-lg">Add Work Experience</div>
            </template>
            <template #body>
                <!-- Organisation -->
                <InputLabel
                    for="organisation"
                    value="Organisation"
                    class="mb-2 required"
                />
                <Input
                    id="organisation"
                    ref="organisation"
                    type="text"
                    placeholder="enter your organisation"
                    v-model="form.organisation"
                    required
                />
                <div v-if="form.errors.organisation">
                    <p class="mb-4 text-sm text-red-600">{{ form.errors.organisation }}</p>
                </div>

                <!-- Position -->
                <InputLabel
                    for="position"
                    value="Position"
                    class="mb-2 required"
                />
                <Input
                    id="position"
                    placeholder="enter your position"
                    label="Position"
                    class="mb-2"
                    v-model="form.position"
                    required
                />
                <div v-if="form.errors.position">
                    <p class="mb-4 text-sm text-red-600">{{ form.errors.position }}</p>
                </div>

                <!-- Responsibilities -->
                <Alert type="info" class="mt-3 mb-2"
                    >Please provide only a brief summary of roles and
                    responsibilities.
                </Alert>
                <InputLabel
                    for="message"
                    value="Responsibilities"
                    class="mb-2 required"
                />
                <textarea
                    id="message"
                    rows="4"
                    class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 mb-2"
                    placeholder="Please provide only a brief summary of roles and responsibilities..."
                    v-model="form.responsibilities"
                    required
                ></textarea>
                <div v-if="form.errors.responsibilities">
                    <p class="mb-4 text-sm text-red-600">{{ form.errors.responsibilities }}</p>
                </div>

                <!-- From Date -->
                <InputLabel for="from_date" value="From Date" class="mb-2 required" />
                <div class="relative max-w-sm mb-3">
                    <input
                        type="date"
                        id="from_date"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Select date"
                        v-model="form.from_date"
                        required
                    />
                </div>
                <div v-if="form.errors.from_date">
                    <p class="mb-4 text-sm text-red-600">{{ form.errors.from_date }}</p>
                </div>

                <!-- To Date -->
                <InputLabel for="to_date" value="To Date" class="mb-2 required" />
                <div class="relative max-w-sm mb-3">
                    <input
                        type="date"
                        id="to_date"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Select date"
                        v-model="form.to_date"
                        required
                    />
                </div>
                <div v-if="form.errors.to_date">
                    <p class="mb-4 text-sm text-red-600">{{ form.errors.to_date }}</p>
                </div>
            </template>
            <template #footer>
                <div class="flex justify-between">
                    <button
                        @click="closeModal"
                        type="button"
                        class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                    >
                        Add
                    </button>
                </div>
            </template>
        </Modal>
    </form>
</template>
