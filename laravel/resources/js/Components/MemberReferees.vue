<script setup>
import { Link, useForm } from "@inertiajs/vue3";
import { Alert, Button, Input } from "flowbite-vue";
import DialogModal from "./DialogModal.vue";
import DeleteConfirmationModal from "./DeleteConfirmationModal.vue";
import InputError from "./InputError.vue";
import { computed, ref } from "vue";
import MemberRefereesList from "./MemberRefereesList.vue";
import AddButton from "@/Components/AddButton.vue";
import DeleteButton from "@/Components/DeleteButton.vue";
import UpdateButton from "@/Components/UpdateButton.vue";
import CancelButton from "@/Components/CancelButton.vue";

const props = defineProps({
  member_id: Number,
  list: {
    type: Object,
    default: [],
  },
});

const showFormModal = ref(false);
const showConfirmationModal = ref(false);

function closeModal() {
  showFormModal.value = false;
}

function closeModalAndResetForm() {
  closeModal();
  itemId.value = -1;
  form.clearErrors();
  form.reset();
}

function showModal() {
  showFormModal.value = true;
}

const canAdd = computed(() => {
  return itemId.value < 0;
});

const canEdit = computed(() => {
  return !canAdd.value;
});

const refereesList = props.list;
console.log(refereesList);
const itemId = ref(-1);

const form = useForm({
  name: "",
  organisation: "",
  phone: "",
  email: "",
});

function edit(id) {
  itemId.value = id;
  const item = refereesList.find(x => x.id === id);
  form.name = item.name;
  form.organisation = item.organisation;
  form.phone = item.phone;
  form.email = item.email;
  showModal();
}

function update(id) {
  form.put(
    route("members.referees.update", {
      member: props.member_id,
      referee: itemId.value,
    }),
    {
      onSuccess(res) {
        let item = refereesList.find(x => x.id === itemId.value);
        item.name = form.name;
        item.organisation = form.organisation;
        item.phone = form.phone;
        item.email = form.email;

        // reset form
        closeModalAndResetForm();
      },
    }
  );
}

function deleteReferee(id) {
  form.delete(
    route("members.referees.destroy", {
      member: props.member_id,
      referee: itemId.value,
    }),
    {
      preserveScroll: true,
      resetOnSuccess: false,
      onSuccess: function () {
        let item = refereesList.find(x => x.id === itemId.value);
        refereesList.splice(refereesList.indexOf(item), 1);
        closeModalAndResetForm();
        showConfirmationModal.value = false;
      },
    }
  );
}

function submit() {
  form.post(route("members.referees.store", props.member_id), {
    onSuccess(res) {
      let formCopy = Object.assign({}, form);
      formCopy.id = res.props.flash.data.id;
      refereesList.push(formCopy);

      // reset form
      closeModalAndResetForm();
    },
  });
}
</script>
<template>
  <div>
    <h5>Referees</h5>
    <Alert type="info" class="mb-2 mt-3"
      >Please provide details for someone how can verify roles and
      responsibilities.
    </Alert>

    <Link href="#">
      <Button class="p-3 mt-3" color="alternative" @click.prevent="showModal"
        >Add Referee</Button
      >
    </Link>
    <MemberRefereesList :list="refereesList" @edit-item="edit" />
  </div>

  <!-- Modal -->
  <DialogModal :show="showFormModal" @close="closeModalAndResetForm">
    <template #title>
      <div class="flex items-center text-lg">
        <span v-if="itemId < 0"> Add Referee </span>
        <span v-else> Edit Referee </span>
      </div>
    </template>
    <template #content>
      <Input
        v-model="form.name"
        placeholder="enter your referees name"
        label="Name"
        class="mb-2"
        required
      />
      <InputError class="mt-2" :message="form.errors.name" />

      <Input
        v-model="form.organisation"
        placeholder="enter your referees organisation"
        label="Organisation"
        class="mb-2"
        required
      />
      <InputError class="mt-2" :message="form.errors.organisation" />

      <Input
        v-model="form.phone"
        placeholder="enter your referees phone"
        label="Phone"
        class="mb-2"
        required
      />
      <InputError class="mt-2" :message="form.errors.phone" />

      <Input
        v-model="form.email"
        name="referee_email"
        placeholder="enter your referees email"
        label="Email"
        class="mb-2"
        required
      />
      <InputError class="mt-2" :message="form.errors.email" />
    </template>
    <template #footer>
      <CancelButton @click="closeModalAndResetForm" />
      <div>
        <AddButton v-if="canAdd" @click="submit" />
        <DeleteButton v-if="canEdit" @click="showConfirmationModal = true" />
        <UpdateButton v-if="canEdit" @click="update" />
      </div>
    </template>
  </DialogModal>
  <DeleteConfirmationModal
    :show="showConfirmationModal"
    @delete="deleteReferee"
    @close="showConfirmationModal = false"
  />
</template>
