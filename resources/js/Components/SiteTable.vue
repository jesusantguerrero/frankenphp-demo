<script setup lang="ts">
import type { ISite } from "@/utils";
import { reactive, ref } from "vue";
import SiteItem from "./SiteItem.vue";
import SiteForm from "./SiteForm.vue";
import { useForm } from "@inertiajs/vue3";

defineProps<{ sites: ISite[] }>();
const emit = defineEmits(["saved", "deleted"]);

const isAdding = ref(false);
const toggleAdding = () => {
  isAdding.value = !isAdding.value;
};
interface ISiteData {
  id?: string;
  action: string;
  value: string;
  index?: number;
}

const siteFormData = useForm({
    id: undefined,
    action: "",
    value: "",
    index: 0,
});
const onSubmit = (newData: ISiteData) => {
  if (siteFormData.processing) return;
  const formData = {
    id: siteFormData?.id ?? null,
    ...newData,
    actions: [
      {
        action: newData.action,
        value: newData.value,
        index: newData.index || 0,
      },
    ],
  };

  const endpoint = `/sites/${siteFormData?.id ?? ''}`;
  const method = siteFormData?.id ? "put" : "post";
  siteFormData
    .transform(() => ({...formData}))
    .submit(method, endpoint, {
        onSuccess() {
            isAdding.value = false;
            emit('saved')
            siteFormData.reset()
        }
    })

};

const onDelete = (site: ISite) => {
  if (confirm(`Delete site ${site.title}?`)) {
    fetch(`/sites/${site.id}`, { method: "DELETE" }).then(() => {
      emit("deleted", site);
    });
  }
};

const onEdit = (site: ISite) => {
    siteFormData.id = site.id;
    siteFormData.title = site.title;
    siteFormData.selector = site.url;
    siteFormData.actions = site.actions;
    siteFormData.url = site.url;
    isAdding.value = true;
}

const onCancel = () => {
    isAdding.value = false;
    siteFormData.reset()
}
</script>

<template>
  <div class="mx-auto max-w-7xl">
    <header class="flex justify-between mb-5">
      <section>
        <h4 class="font-bold text-2x">Sites</h4>
      </section>
      <section class="flex space-x-2">
        <button
          class="px-5 py-1 bg-gray-600 rounded-md text-md"
          @click="toggleAdding"
        >
          Add
        </button>
        <button
          class="px-5 bg-gray-600 rounded-md text-md"
          @click="$emit('check')"
        >
          Check sites
        </button>
      </section>
    </header>
    <section class="overflow-hidden rounded-md">
      <SiteForm
        v-if="isAdding"
        :site-data="siteFormData"
        :is-loading="siteFormData.processing"
        @cancel="isAdding = false"
        @submit="onSubmit"
      />
      <SiteItem
        v-for="site in sites"
        :site="site"
        :key="site.id"
        @edit="onEdit(site)"
        @deleted="$emit('deleted', site)"
      />
    </section>
  </div>
</template>
