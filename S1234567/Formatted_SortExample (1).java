
import java.util.Arrays;

/**
 * Demonstrates sorting using Quick Sort and Merge Sort algorithms.
 */
public class SortExample {

    public static void main(String[] args) {
        // Unsorted arrays
        Integer[] array1 = { 12, 13, 24, 10, 3, 6, 90, 70 };
        int[] array2 = { 2, 6, 3, 5, 1 };

        // Sort using Quick Sort
        quickSort(array1, 0, array1.length - 1);
        System.out.println("Quick Sorted array: " + Arrays.toString(array1));

        // Sort using Merge Sort
        mergeSort(array2, array2.length);
        System.out.println("Merge Sorted array: " + Arrays.toString(array2));
    }

    /**
     * Implements the Quick Sort algorithm.
     *
     * @param arr  The array to sort
     * @param low  Starting index
     * @param high Ending index
     */
    public static void quickSort(Integer[] arr, int low, int high) {
        if (arr == null || arr.length == 0 || low >= high) return;

        // Choose the pivot element
        int middle = low + (high - low) / 2;
        int pivot = arr[middle];

        // Partition the array around the pivot
        int i = low, j = high;
        while (i <= j) {
            while (arr[i] < pivot) i++;
            while (arr[j] > pivot) j--;

            if (i <= j) {
                swap(arr, i, j);
                i++;
                j--;
            }
        }

        // Recursively sort the partitions
        if (low < j) quickSort(arr, low, j);
        if (high > i) quickSort(arr, i, high);
    }

    /**
     * Swaps two elements in the array.
     */
    public static void swap(Integer[] array, int x, int y) {
        int temp = array[x];
        array[x] = array[y];
        array[y] = temp;
    }

    /**
     * Implements the Merge Sort algorithm.
     *
     * @param a The array to sort
     * @param n Length of the array
     */
    public static void mergeSort(int[] a, int n) {
        if (n < 2) return;

        int mid = n / 2;
        int[] left = new int[mid];
        int[] right = new int[n - mid];

        System.arraycopy(a, 0, left, 0, mid);
        System.arraycopy(a, mid, right, 0, n - mid);

        mergeSort(left, mid);
        mergeSort(right, n - mid);
        merge(a, left, right, mid, n - mid);
    }

    /**
     * Merges two subarrays into the original array.
     */
    public static void merge(int[] a, int[] left, int[] right, int leftSize, int rightSize) {
        int i = 0, j = 0, k = 0;

        while (i < leftSize && j < rightSize) {
            if (left[i] <= right[j]) {
                a[k++] = left[i++];
            } else {
                a[k++] = right[j++];
            }
        }

        while (i < leftSize) {
            a[k++] = left[i++];
        }

        while (j < rightSize) {
            a[k++] = right[j++];
        }
    }

    /**
     * Checks if the array is sorted in ascending order.
     */
    private static boolean isSorted(int[] x) {
        for (int i = 0; i < x.length - 1; i++) {
            if (x[i] > x[i + 1]) return false;
        }
        return true;
    }
}
