const processOrder = async () => {
    try {
        const response = await axios.post(`/api/orders/process?id=${orderId}`);
        console.log('Process Order Response:', response.data); // Debugging

        if (response.data.success) {
            alert('Order processed successfully.');
            // Update the order status in the UI
            order.status = response.data.status;
        } else {
            alert('Failed to process the order: ' + (response.data.message || 'Unknown error'));
        }
    } catch (error) {
        console.error('Error processing order:', error);
        alert('An error occurred while processing the order.');
    }
};