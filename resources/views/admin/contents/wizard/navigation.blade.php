<div class="flex justify-between pt-6 border-t border-zinc-800">

    <!-- Previous -->

    <button
        type="button"
        @click="prev()"
        x-show="step > 1"
        class="px-5 py-2 bg-zinc-700 hover:bg-zinc-600 rounded">

        Previous

    </button>

    <!-- Next -->

    <button
        type="button"
        @click="next()"
        x-show="step < steps().length"
        class="px-6 py-2 bg-red-600 hover:bg-red-500 rounded ml-auto">

        Next

    </button>

    <!-- Save -->

    <button
        type="submit"
        x-show="step === steps().length"
        class="px-6 py-2 bg-green-600 hover:bg-green-500 rounded ml-auto">

        Save Content

    </button>

</div>