<template>
    <jet-form-section @submitted="createPost">
        <template #title>
            TCreate a new post
        </template>

        <template #description>
            Let's talk about something...
        </template>

        <template #form>

            <div class="col-span-6 sm:col-span-4">
                <jet-label for="title" value="Subject" />
                <jet-input id="title" type="text" class="mt-1 block w-full" v-model="form.title" autofocus />
                <jet-input-error :message="form.error('title')" class="mt-2" />
                <jet-label for="content" value="Your message" />
                <jet-input id="content" type="text" class="mt-1 block w-full" v-model="form.content" autofocus />
                <jet-input-error :message="form.error('content')" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                Saved.
            </jet-action-message>

            <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Save
            </jet-button>
        </template>
    </jet-form-section>
</template>

<script>
    import JetActionMessage from './../../Jetstream/ActionMessage'
    import JetButton from './../../Jetstream/Button'
    import JetFormSection from './../../Jetstream/FormSection'
    import JetInput from './../../Jetstream/Input'
    import JetInputError from './../../Jetstream/InputError'
    import JetLabel from './../../Jetstream/Label'

    export default {
        components: {
            JetActionMessage,
            JetButton,
            JetFormSection,
            JetInput,
            JetInputError,
            JetLabel,
        },

        data() {
            return {
                form: this.$inertia.form({
                    title: '',
                    content: ''
                },
                {
                    bag: 'createPost',
                    resetOnSuccess: false,
                }
                )
            }
        },

        methods: {
            createPost() {
                this.form.post('/custom/post/create', {
                    preserveScroll: true
                });
            },
        },
    }
</script>
