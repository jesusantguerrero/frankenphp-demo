<script lang="ts" setup>
import type { ISite } from "@/utils";
import { computed, reactive, watch } from "vue";

const props = defineProps<{
  isLoading: boolean;
  siteData: ISite;
}>();

const site = reactive({
  title: "",
  url: "",
  selectorTemplate: "github",
  selector: "",
});

watch(
  () => props.siteData,
  (data) => {
    site.selector = data?.selector ?? "";
    site.title = data.title;
    site.url = data.url;
    site.selectorTemplate = data?.selectorTemplate ?? "";
  }
);

const isGithubSite = computed(() => site.selectorTemplate == "github");
</script>

<template>
  <form
    @submit.prevent="$emit('submit', site)"
    class="justify-between px-5 py-5 space-x-2 bg-gray-800"
  >
    <div class="flex w-full space-x-2 text-left">
      <div class="flex flex-col w-full">
        <label htmlFor="title" class="px-5 text-xl">Title</label>
        <input
          type="text"
          name="title"
          id="title"
          v-model="site.title"
          class="w-full px-5 py-2 text-white bg-gray-700 rounded-md"
        />
      </div>
      <div class="flex flex-col w-full">
        <label htmlFor="url" class="px-5 text-xl">URL</label>
        <input
          type="text"
          name="url"
          id="url"
          class="w-full px-5 py-2 text-white bg-gray-700 rounded-md"
          v-model="site.url"
        />
      </div>
      <div class="flex flex-col w-full">
        <label htmlFor="selector" class="px-5 text-xl">Selector</label>
        <div
          class="flex space-x-2"
        >
          <select
            name="selectorTemplate"
            id="selectorTemplate"
            v-model="site.selectorTemplate"
            class="w-full px-5 py-2 text-white bg-gray-700 rounded-md"
          >
            <option value="github">Github</option>
            <option value="custom">Custom</option>
          </select>
          <input
            v-if="!isGithubSite"
            type="text"
            name="selector"
            id="selector"
            class="w-full px-5 py-2 text-white bg-gray-700 rounded-md"
            v-model="site.selector"
          />
        </div>
      </div>
      <div class="flex flex-col w-full" v-if="!isGithubSite">
        <label htmlFor="version" class="px-5 text-xl">Action</label>
        <div class="flex space-x-2">
          <input
            type="text"
            name="action"
            id="action"
            placeholder="Name"
            class="w-full px-5 py-2 text-white bg-gray-700 rounded-md"
            v-model="site.action"
          />
          <input
            type="text"
            name="value"
            id="value"
            placeholder="value"
            class="w-full px-5 py-2 text-white bg-gray-700 rounded-md"
            v-model="site.value"
          />
          <input
            type="number"
            name="index"
            id="index"
            placeholder="index"
            class="w-16 px-1 py-2 text-white bg-gray-700 rounded-md"
            v-model="site.index"
          />
        </div>
      </div>
    </div>
    <div class="flex justify-end mt-5 space-x-3">
      <button
        type="button"
        class="px-5 py-2 bg-red-400 rounded-md text-md"
        @click="$emit('cancel')"
        :disabled="isLoading"
        v-if="!isLoading"
      >
        Cancel
      </button>
      <button class="px-5 py-2 bg-green-400 rounded-md text-md" :disabled="isLoading">
        Save
      </button>
    </div>
  </form>
</template>
